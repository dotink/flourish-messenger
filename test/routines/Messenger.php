<?php namespace Dotink\Lab
{
	use Dotink\Flourish\Messenger;

	return [

		'setup' => function($data){
			needs($data['root'] . '/src/Messenger.php');
			needs($data['root'] . '/src/Message.php');

		},

		'tests' => [

			//
			//
			//

			'Instantiation [Name Only]' => function($data, $shared)
			{
				$shared->messenger = new Messenger();
			},

			//
			//
			//

			'Record Message' => function($data, $shared)
			{
                $message1 = $shared->messenger->record('test');
                $message2 = $shared->messenger->record('success', 'test');

                assert($message1->content)->equals('test');

                assert($message2->name)->equals('success');
                assert($message2->content)->equals('test');

			},

			//
			//
			//

			'Retrieve Message' => function($data, $shared)
			{
                $message = $shared->messenger->retrieve('success');

                assert($message->name)->equals('success');
                assert($message->content)->equals('test');
			},


			//
			//
			//
			'Compose Message' => function($data, $shared)
			{
                $shared->messenger->record('error', 'working');

                assert($shared->messenger->compose('error'))->equals('error: working');
			},
		]
	];
}
