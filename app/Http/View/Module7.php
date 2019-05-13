<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2019-05-08
 * Time: 18:06
 */

namespace App\Http\View;


use App\Cms\AbstractViewable;
use App\Models\CmsServiceEnter;

class Module7 extends AbstractViewable
{

    protected $view = "web.module-7";

    protected $css = ["css/web/module_7.css",];

    protected $is_leaf = true;

    protected function getService()
    {
        $services = CmsServiceEnter::all();

        $service1 = [];
        $service2 = [];
        $service3 = [];

        for($i = 0; $i<count($services); $i++){
            if($i<3){
                $service1[] = $services[$i];
            }elseif($i<6){
                $service2[] = $services[$i];
            }else{
                $service3[] = $services[$i];
            }
        }

        $service = [
            "service1" => $service1,
            "service2" => $service2,
            "service3" => $service3,
        ];
        return $service;
    }

    public function render()
    {

        return view($this->view, [
            "services" => $this->getService()
        ]);
        // TODO: Implement render() method.
    }
}