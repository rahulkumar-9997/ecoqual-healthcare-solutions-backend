<?php
namespace App\Mail;
use Illuminate\Mail\Mailable;
class ContactUsMail extends Mailable
{
    public $data;
    public function __construct(array $data) {
        $this->data = $data;
    }

    public function build() {
        return $this->view('frontend.pages.email.contact-us-mail')
            ->subject('Contact Us Message')
            ->with([
                'name' => $this->data['name'],
                'email' => $this->data['email'],
                'phone' => $this->data['mobile_number'],
                'message' => $this->data['message'],
            ]);
    }
}
