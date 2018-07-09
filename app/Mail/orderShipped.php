<?php
 
namespace App\Mail;
 
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
 
class orderShipped extends Mailable
{
    use Queueable, SerializesModels;
     
    /**
     * The demo object instance.
     *
     * @var Demo
     */
    public $products;
    public $formInfo;
 
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($products, $formInfo)
    {
        $this->products = $products;
        $this->formInfo = $formInfo;
    }
 
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.orderShipped');
    }
}