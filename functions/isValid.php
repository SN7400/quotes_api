<?php
    function isValid($id, &$model) {
        $model->$id = $id;
        return $model->read_single();
    }

    /*
    Let's step through the isValid helper function I created. 

    It receives 2 parameters: id and model. This lets me use it for any model. 
    
    3 total lines inside the function:
    Set the id on the model
    Call the read_single method from the model
    Return the result
    
    This helper (aka utility) function comes in handy. It's the only one I created in this project. 
    
    This will let you confirm something exists before trying to modify it.
    */