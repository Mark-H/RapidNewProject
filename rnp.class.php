<?php

class RapidNewProject {
    public $config = array();
    public $modxRoots = array();

    public function __construct(array $options = array(), array $modxRoots = array()) {
        $this->config = array_merge(array(
            'basePath' => dirname(__FILE__).'/',
            'formTpl' => dirname(__FILE__).'/rnp.form.tpl',
            'baseUrl' => trim(str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname(__FILE__)), '/') . '/',
            'defaultTargetPath' => dirname(dirname(__FILE__)) . '/',
            'defaultTargetUrl' => '/' . trim(str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname(dirname(__FILE__))), '/') . '/',
        ), $options);

        $this->modxRoots = $modxRoots;
    }

    public function showForm($values) {
        $fields = array_merge(array(
            'rnp_submit' => 'Go go go!',
            'modx_root' => '',
            'namespace' => '',
            'target_path' => $this->config['defaultTargetPath'],
            'target_url' => $this->config['defaultTargetUrl'],
        ), $values);
        $fields['modx_root_input'] = $this->generateRootInputs($values);

        $form = file_get_contents($this->config['formTpl']);

        foreach ($fields as $fld => $value) {
            $form = str_replace('{' . $fld . '}', $value, $form);
        }

        return $form;


    }

    private function generateRootInputs($values) {
        $current = (isset($values['modx_root']) && !empty($values['modx_root'])) ?
            $values['modx_root'] : '';

        $return = array();
        foreach ($this->modxRoots as $id => $properties) {
            $return[] = '<input type="radio" name="modx_root" value="' . $id . '" /> ' . $properties['name'];
        }

        return implode("\n", $return);
    }
}
