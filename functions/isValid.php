<?php
    function isValid($id, &$model) {
        $found = $model->read_single();
        if(isset($found->id)) {
            return true;
        } else {
            return false;
        }
    }