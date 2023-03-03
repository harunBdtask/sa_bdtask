<?php namespace App\Libraries;

class Email extends \CodeIgniter\Email\Email{
    protected function validateEmailForShell(&$email){
        return TRUE;
    }
}