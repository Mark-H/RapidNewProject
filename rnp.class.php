<?php

class RapidNewProject {
    public $config = array();
    public $modxRoots = array();

    /* @var modX $modx */
    public $modx = null;

    public function __construct(array $options = array(), array $modxRoots = array()) {
        $this->config = array_merge(array(
            'basePath' => dirname(__FILE__).'/',
            'formTpl' => dirname(__FILE__).'/rnp.form.tpl',
            'baseUrl' => '/' . trim(str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname(__FILE__)), '/') . '/',
            'defaultTargetPath' => dirname(dirname(__FILE__)) . '/',
            'defaultTargetUrl' => '/' . trim(str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname(dirname(__FILE__))), '/') . '/',
        ), $options);

        $this->modxRoots = $modxRoots;
    }

    public function showForm($values) {
        $fields = array_merge($this->config,array(
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

    public function build(array $p = array()) {
        $p = $this->fixPaths($p);
        echo '<pre>'.print_r($p, true).'</pre>';

        if (isset($p['modx_root']) && isset($this->modxRoots[$p['modx_root']])) {
            $root = $this->modxRoots[$p['modx_root']];
            echo '<pre>'.print_r($root, true).'</pre>';

            require_once $root['path'] . 'config.core.php';
            require_once MODX_CORE_PATH.'model/modx/modx.class.php';
        }

        if (!class_exists('modX')) {
            return 'Error: could not load modX class for specified modx_root';
        }

        $this->modx = new modX();
        $this->modx->initialize('mgr');
        $this->modx->getService('error','error.modError');

        $this->addNamespace($p['namespace'], $p['target_path']);
        $this->addPathSettings($p['namespace'], $p['target_path'], $p['target_url']);

        return 'Namespace & Path Settings Created.';
    }

    private function addNamespace($namespace, $path) {
        /* @var modNamespace $ns */
        $ns = $this->modx->getObject('modNamespace', array('name' => $namespace));
        if (!($ns instanceof modNamespace)) {
            $ns = $this->modx->newObject('modNamespace');
            $ns->set('name', $namespace);
        }

        $ns->set('path', $path . 'core/components/' . $namespace . '/');
        if (isset($ns->_fieldMeta['assets_path'])) {
            $ns->set('assets_path', $path . 'assets/components/' . $namespace . '/');
        }
        $ns->save();
    }

    private function fixPaths(array $p = array()) {
        foreach ($p as $key => $value) {
            if (strstr($key, 'path') || strstr($key,'url')) {
                $value = trim($value,'/\\');
                $value = '/' . $value . '/';
                $p[$key] = $value;
            }
        }
        return $p;
    }

    private function addPathSettings($namespace, $path, $url) {
        /* @var modSystemSetting $setting */

        /* namespace.core_path */
        $setting = $this->modx->getObject('modSystemSetting', array('key' => $namespace.'.core_path'));
        if (!$setting) {
            $setting = $this->modx->newObject('modSystemSetting');
            $setting->set('key', $namespace.'.core_path');
        }
        $setting->fromArray(array(
            'value' => $path . 'core/components/'.$namespace.'/',
            'xtype' => 'textfield',
            'namespace' => $namespace,
        ), '', true);
        $setting->save();


        /* namespace.assets_path */
        $setting = $this->modx->getObject('modSystemSetting', array('key' => $namespace.'.assets_path'));
        if (!$setting) {
            $setting = $this->modx->newObject('modSystemSetting');
            $setting->set('key', $namespace.'.assets_path');
        }
        $setting->fromArray(array(
            'value' => $path . 'assets/components/'.$namespace.'/',
            'xtype' => 'textfield',
            'namespace' => $namespace,
        ), '', true);
        $setting->save();


        /* namespace.assets_url */
        $setting = $this->modx->getObject('modSystemSetting', array('key' => $namespace.'.assets_url'));
        if (!$setting) {
            $setting = $this->modx->newObject('modSystemSetting');
            $setting->set('key', $namespace.'.assets_url');
        }
        $setting->fromArray(array(
            'value' => $url . 'assets/components/'.$namespace.'/',
            'xtype' => 'textfield',
            'namespace' => $namespace,
        ), '', true);
        $setting->save();


    }
}
