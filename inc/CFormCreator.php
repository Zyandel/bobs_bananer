<?php

class CFormCreator
{
	public function __construct(CApp &$app)
	{
		$this->m_app = $app;
	}

	public function open()
	{
		echo('<form method="post">');
	}

	public function close()
	{
		echo('</form>');
	}

	public function createText(string $name, string $label)
	{
		$this->createInputOfType("text", $name, $label);
	}

	public function createEmail(string $name, string $label)
	{
		$this->createInputOfType("email", $name, $label);
	}

	public function createPassword(string $name, string $label)
	{
		$this->createInputOfType("password", $name, $label);
	}

	public function createInputOfType(string $type, string $name, string $label)
	{
		$value = isset($this->m_preset[$name]) ? $this->m_preset[$name] : "";
		if($type == "password")
		{
			$value = "";
		}

		echo('<div class="formContainer">');
		echo('<label for="' . $name . '">' . $label . ': </label>');
		echo('<input value="' . $value . '" type="' . $type . '" id="' . $name . '" name="' . $name . '" />');
		echo('</div>');
	}

	public function createSubmit(string $label)
	{
		echo('<input type="submit" value="' . $label . '"/>');
	}

	public function presetData(array $values)
	{
		$this->m_preset = $values;
	}

	///////////////// Member variables ///////////////////////
	private $m_app = NULL;
	private $m_preset = [];
};


?>