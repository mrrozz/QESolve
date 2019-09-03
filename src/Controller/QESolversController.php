<?php
// src/Controller/QESolversController.php

namespace App\Controller;

use Cake\ORM\TableRegistry;

class QESolversController extends AppController
{

    /**
    * App start, set form validation token.
    *
    * @return void
    */
    public function index()
    {
        $token = sha1( time() . rand(0, 9999) );
        $this->request->session()->write('token', $token);
        $this->set('token', $token);
    }

    /**
    * Validate good input data for Quadratic Equationn
    *
    * @return void
    */
    public function solveEquation()
    {
        // Set up our cakephp freindly JSON output type
        $this->autoRender = false; 
        $this->RequestHandler->respondAs('json');
        $this->response->type('application/json');

        // Build the output JSON object
        $json_co               = new class{};
        $json_co->token        = null;
        $json_co->efield       = '';
        $json_co->answer       = 'Error';
        $json_co->recurring    = 0;

        // Get Request Data
        $input_a = $this->request->data('input-a');
        $input_b = $this->request->data('input-b');
        $input_c = $this->request->data('input-c');

        // Check for Zero in case they got around the javascript.
        if( $input_a == 0 ){
            $json_co->answer       = 'Value A can not be 0.';
            $json_co->efield       = 'input-a';
        }

        // Check for numeric in case they got around the javascript.
        if( !is_numeric($input_a) || !is_numeric($input_b) || !is_numeric($input_c) ){
            $efield_id = ( !is_numeric($input_a) ) ? 'a' : (( !is_numeric($input_b) ) ? 'b' : 'c');
            $json_co->efield       = 'input-'.$efield_id;
            $json_co->answer       = 'All values must be numeric.';
        }

        // Verify no input errors & Verify post & secondary form validation token
        if( $json_co->answer == 'Error' && 
            $this->request->isPost() && 
            $this->request->session()->read('token') == $this->request->data('token') &&
            !empty($this->request->session()->read('token')) ){
            $this->useModel1($json_co,$input_a,$input_b,$input_c);
        }

        // Build a new form validation token
        $json_co->token = sha1( time() . rand(0,9999) );
        $this->request->session()->write('token', $json_co->token);

        // Send the JSON obect back to our javascript.
        echo json_encode( $json_co );
    }

    /**
    * Execute the Quadratic Equation
    * In several MVC frameworks you would put this in a model
    * I've already got over 20 hours on this test project and can't spend
    * more time trying to figure it out while they are busy updating
    * their documention.
    *
    * The CakePHP developers I spoke with are not clear on where
    * business logic should go either as they are still arguing about it as of 9/2/2019.
    *
    * @return void
    */
    private function useModel1(&$json_co,$input_a,$input_b,$input_c)
    {
        // Let's check to see if anyone asked for this equation before
        $equation_id = ($input_a.'~'.$input_b.'~'.$input_c);
        $responseLogTable = TableRegistry::getTableLocator()->get('ResponseLogs');
        $requestLogTable = TableRegistry::getTableLocator()->get('RequestLogs');
        $requestLog = $requestLogTable->find()->where(['id' => $equation_id])->first();
        if( isset($requestLog->id) ){
            // We already handled this equation, update number of times euqation was used.
            $requestLog->count++;
            $json_co->recurring    = $requestLog->count;
            $requestLogTable->save($requestLog);

            // Report our recorded findings
            $responseLog = $responseLogTable->find()->where(['id' => $equation_id])->first();
            $json_co->answer = $responseLog->response;
        } else {
            // Calculate the Quadratic Equation
            $output_d = pow($input_b,2) - 4*$input_a*$input_c;
            if ($output_d == 0) {
                $json_co->answer = "x = ". -$input_b / 2.0 / $input_a;
            }
            if ($output_d > 0) {
                $json_co->answer = "x1 = " . (-$input_b + sqrt($output_d)) / 2.0 / $input_a . "<br>" . "x2 = " . (-$input_b - sqrt($output_d)) / 2.0 / $input_a;
            } else {
                $json_co->answer = "No Solution";
            }
            // Create a new request log
            $requestLog = $requestLogTable->newEntity();
            $requestLog->id = $equation_id;
            $requestLog->a = $input_a;
            $requestLog->b = $input_b;
            $requestLog->c = $input_c;
            $requestLog->count = 1;
            $requestLogTable->save($requestLog);

            // Create a new Response log
            $responseLog = $responseLogTable->newEntity();
            $responseLog->id = $equation_id;
            $responseLog->response = $json_co->answer;
            $responseLogTable->save($responseLog);

            $json_co->recurring    = 1;
        }
    }
}

