<?php

namespace App\Notifications;

use App\Classes\Notify;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MainNotificaiton extends Notification implements ShouldQueue
{
	use Queueable;

	public $notficationData;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct($notficationData)
	{
		$this->notficationData = $notficationData;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @param  mixed  $notifiable
	 * @return array
	 */
	public function via($notifiable)
	{
		$via = ['database'];

		if ($this->notficationData['mail']['isAbleToSend']) {
			$via[] = 'mail';
		}

		return $via;
	}

	/**
	 * Get the mail representation of the notification.
	 *
	 * @param  mixed  $notifiable
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail($notifiable)
	{
		$subject = $this->notficationData['mail']['title'];
		$content = $this->notficationData['mail']['content'];

		return (new MailMessage)
			->subject($subject)
			->greeting('Hello!')
			->line($content);
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @param  mixed  $notifiable
	 * @return array
	 */
	public function toArray($notifiable)
	{
		return $this->notficationData;
	}
}
