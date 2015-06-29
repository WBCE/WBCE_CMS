<?php

function qx_const($const) {
        $retval = '{'.$const.'}';
        if(defined($const)) {
                $retval = constant($const);
        }
        return $retval;
}