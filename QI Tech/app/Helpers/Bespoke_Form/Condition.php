<?php

namespace App\Helpers\Bespoke_Form;

class Condition
{
	public string $if_value;
    public string $action_type;
    public string $value;

	public function __construct(string $if_value, string $action_type, $value)
	{
		$this->if_value = $if_value;
        $this->action_type = $action_type;
        $this->value = $value;
	}

    public function process_condition()
    {
        $priority = 0;
        switch($this->action_type)
        {
            case 'add_priority_value':
                $priority += $this->value;
        }

        return (object)['priority' => $priority];
    }
}