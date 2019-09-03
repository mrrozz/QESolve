<!-- File: src/Template/QESolvers/index.ctp -->

<div class="solver_conatiner">
    <div id="note"></div>
    <h1>QESolver</h1>
    <div class="fine">We solve day or night without Coffee!</div>
    <div id="quadeq_example">Ax&#178;+Bx+C=0</div>
    <?php
        echo $this->Form->create(null,['id'=>'qesolver_form']); 
        echo $this->Form->control('token'    , ['value'=>"$token", 'type'=>'hidden']);
        echo $this->Form->control('Input A: ', ['name'=>'input-a', 'type' => 'text', 'value' => "", 'placeholder'=>"0"]);
        echo $this->Form->control('Input B: ', ['name'=>'input-b', 'type' => 'text', 'value' => "", 'placeholder'=>"0"]);
        echo $this->Form->control('Input C: ', ['name'=>'input-c', 'type' => 'text', 'value' => "", 'placeholder'=>"0"]);
        echo '<div id="qesolver_response"></div>';
        echo '<div id="button_div"><div id="qesolver_button_submit" class="noselect button">Submit</div></div>';
        echo $this->Form->end();
        /* 
            NOTE: I was unable to discover how CakePHP handles hidden form elements to retrieve the expected variable. 
            So I cheated by letting the browser draw the form, then using some inline Javascript to alter the value of the
            hidden field created by CakePHP.
        */
    ?>
    <script> document.getElementById("token").value= "<?=$token;?>"; </script>
</div>