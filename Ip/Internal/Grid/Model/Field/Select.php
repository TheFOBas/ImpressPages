<?php
/**
 * @package   ImpressPages
 */

namespace Ip\Internal\Grid\Model\Field;


class Select extends \Ip\Internal\Grid\Model\Field
{
    protected $field = '';
    protected $label = '';
    protected $defaultValue = '';
    protected $values = array();

    public function __construct($config)
    {
        if (empty($config['field'])) {
            throw new \Ip\Exception('\'field\' option required for text field');
        }
        $this->field = $config['field'];

        if (!empty($config['label'])) {
            $this->label = $config['label'];
        }

        if (!empty($config['values'])) {
            $this->values = $config['values'];
        }

        if (!empty($config['defaultValue'])) {
            $this->defaultValue = $config['defaultValue'];
        }
    }

    public function preview($recordData)
    {
        $previewValue = $recordData[$this->field];
        foreach($this->values as $value) {
            if (is_array($value) && isset($value[1]) && $value[0] == $previewValue) {
                $previewValue = $value[1];
                break;
            }
        }
        return esc($previewValue);
    }

    public function createField()
    {
        $field = new \Ip\Form\Field\Select(array(
            'label' => $this->label,
            'name' => $this->field,
            'values' => $this->values
        ));
        $field->setValue($this->defaultValue);
        return $field;
    }

    public function createData($postData)
    {
        if (isset($postData[$this->field])) {
            return array($this->field => $postData[$this->field]);
        }
        return array();
    }

    public function updateField($curData)
    {
        $field = new \Ip\Form\Field\Select(array(
            'label' => $this->label,
            'name' => $this->field,
            'values' => $this->values
        ));
        $field->setValue($curData[$this->field]);
        return $field;
    }

    public function updateData($postData)
    {
        return array($this->field => $postData[$this->field]);
    }


    public function searchField($searchVariables)
    {
        $values = array(array(null, ''));
        $values = array_merge($values, $this->values);

        $field = new \Ip\Form\Field\Select(array(
            'label' => $this->label,
            'name' => $this->field,
            'values' => $values
        ));
        if (!empty($searchVariables[$this->field])) {
            $field->setValue($searchVariables[$this->field]);
        }
        return $field;
    }

    public function searchQuery($searchVariables)
    {
        if (isset($searchVariables[$this->field]) && $searchVariables[$this->field] !== '') {
            return $this->field . ' = \''.mysql_real_escape_string($searchVariables[$this->field]) . '\' ';
        }

    }
}
