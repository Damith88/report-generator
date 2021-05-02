<?php

class ReportDefinition
{

    const TYPE_CSV = 'csv';
    const TYPE_JSON = 'json';
    const TYPE_HTML = 'html';

    /**
     * @var SimpleXMLElement 
     */
    private $definition = null;

    public function __construct(SimpleXMLElement $definition)
    {
        $this->definition = $definition;
    }

    public static function fromFile($file)
    {
        if (!file_exists($file)) {
            throw new RuntimeException('report definition not found. path: ' . $file);
        }
        return new self(simplexml_load_file($file));
    }

    public function getBaseQuery()
    {
        return (string) $this->definition->query;
    }

    public function getType()
    {
        return (string) $this->definition->type;
    }

    public function getHeaderFields() {
        $fields = [];
        foreach ($this->definition->display_fields->display_field as $displayField) {
            $fields[] = (string) $displayField;
        }
        return $fields;
    }

    public function getFilterFields()
    {
        $fields = [];
        foreach ($this->definition->filter_fields->filter_field as $filterField) {
            $attributes = [];
            foreach ($filterField->attributes() as $attr => $val) {
                $attributes[$attr] = (string) $val;
            }
            $fields[] = $attributes;
        }
        return $fields;
    }

    public function getValidationRules()
    {
        $ruleSet = [];
        foreach ($this->definition->validation_rules->validation_rule as $rule) {
            $ruleSet[(string)$rule['field']] = (string) $rule;
        }
        return $ruleSet;
    }
}
