<?php

namespace App\Helpers\Bespoke_Form\Types;

use App\Helpers\Bespoke_Form\Condition;

class Text
{
	public string $value;
    //public Condition $conditions = [];

	public function __construct(string $value, $conditions)
	{
		$this->value = $value;
        foreach($conditions as $c)
            $this->conditions[] = new Condition($c->if_value, $c->action_type, $c->value);
	}

    public function process_conditions()
    {
        foreach($this->conditions as $c)
        {
            if($c->if_value == $this->value)
            {
                $c->process_condition();
            }
        }
    }
}