<?php
/**
 * Created by PhpStorm.
 * User: abc
 * Date: 2016/11/7
 * Time: 15:20
 */
namespace lbreak\datePicker;

use yii\base\Widget;
use yii\web\View;

/**
 * Class DatePickerWidget
 * @package backend\widgets\datePicker
 * @method View getView()
 */
class DatePickerWidget extends Widget
{
    protected $config = [
        'locale' => [
            'direction' => 'ltr',
        ],
    ];

    public $inputName = 'DatePicker';
    public $id = '';
    public $cssClass = 'date_picker';
    public $prompt = '';
    public $style = 'text-align:center;min-width:100px;width:100px';
    public $autoUpdateInput = false;
    public $single = true;
    public $timePicker = false;
    public $showDropdowns = true;
    public $is24 = true;
    public $applyLabel = '确认';
    public $cancelLabel = '取消';
    public $format = "YYYY-MM-DD";
    public $daysOfWeek = ["日", "一", "二", "三", "四", "五", "六"];
    public $monthNames = ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"];

    public function init()
    {
        parent::init();

        $this->config['singleAutoUpdateInput'] = $this->autoUpdateInput;
        $this->config['singleDatePicker'] = $this->single;
        $this->config['timePicker'] = $this->timePicker;
        $this->config['timePicker24Hour'] = $this->is24;
        $this->config['showDropdowns'] = $this->showDropdowns;
        $this->config['locale']['format'] = $this->format;
        $this->config['locale']['applyLabel'] = $this->applyLabel;
        $this->config['locale']['cancelLabel'] = $this->cancelLabel;
        $this->config['locale']['daysOfWeek'] = $this->daysOfWeek;
        $this->config['locale']['monthNames'] = $this->monthNames;
        if($this->prompt){
            $this->initPrompt();
        }
    }

    public function run()
    {
        $dateInput = "<input name='{$this->inputName}' value='{$this->prompt}'";
        $dateInput .= $this->id ? " id='{$this->id}'" : '';
        $dateInput .= $this->cssClass ? " class='{$this->cssClass}'" : '';
        $dateInput .= $this->style ? " style='{$this->style}'" : '';
        $dateInput .= '>';
        $view = $this->getView();
        DatePickerAsset::register($view);
        echo $dateInput;
        if($this->prompt){
            $this->config['startDate'] = $this->prompt;
        }
        $config = json_encode($this->config);
        if($this->id){
            $script = "$('#{$this->id}').daterangepicker({$config})";
        }else{
            $script = "$('input[name=\"{$this->inputName}\"]').daterangepicker({$config})";
        }
        $view->registerJs($script);
    }

    protected function initPrompt(){
        if(!$this->prompt){
            $this->prompt = '';
        }
        if(strlen((float)$this->prompt) == 10){
            $prompt = $this->prompt;
        }else{
            $prompt = strtotime($this->prompt);
        }

        switch($this->format){
            case "YYYY-MM-DD HH:mm":
                $format = 'Y-m-d H:i';
                break;
            case "YYYY-MM-DD HH:mm:ss":
                $format = 'Y-m-d H:i:s';
                break;
            case "YYYY-M-DD HH:mm:ss":
                $format = 'Y-n-d H:i:s';
                break;
            case "YYYY-MM-DD":
                $format = 'Y-m-d';
                break;
            case "YYYY-M-DD":
                $format = 'Y-n-d';
                break;
            default:
                $format = 'Y-m-d H:i:s';
        }
        $this->prompt = date($format,$prompt);
    }
}