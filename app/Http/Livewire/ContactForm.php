<?php

namespace App\Http\Livewire;

use ImLiam\UniqueGmailAddress\UniqueGmailAddress;
use Livewire\Component;
use Mail;

class ContactForm extends Component
{
    public $name, $email, $comment, $success, $error;
    protected $rules = [
        'name' => 'required',
        'email' => 'required|email',
        'comment' => 'required|min:5'
    ];
    public function contactFormSubmit()
    {
        $contact = $this->validate();
        $myEmail = new UniqueGmailAddress($this->email);
        if (!$myEmail->isGmailAddress()) {
            $this->success = false;
            return $this->error = 'Please use a gmail valid email';
        }
        Mail::send('email',
        array(
            'name' => $this->name,
            'email' => $this->email,
            'comment' => $this->comment
        ), function($message){
            $message->from('franciiscocampos170@gmail.com');
            $message->to('dev.franciscocampos@gmail.com', 'Francisco Campos')->subject('Livewire Contact Form');
        });
        $this->error = false;
        $this->success = 'Thank you for reaching out to us!';
        $this->clearFields();
    }

    private function clearFields()
    {
        $this->name = '';
        $this->email = '';
        $this->comment = '';
    }
    
    public function render()
    {
        return view('livewire.contact-form');
    }
}
