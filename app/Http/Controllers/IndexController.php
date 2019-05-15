<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2018/11/21
 * Time: 10:22
 */

namespace App\Http\Controllers;


use App\Http\View\BreadCrumb;
use App\Http\View\Container;
use App\Http\View\Facade\Cms;
use App\Http\View\Footer;
use App\Http\View\Module1;
use App\Http\View\Module10;
use App\Http\View\Module11;
use App\Http\View\Module12;
use App\Http\View\Module2;
use App\Http\View\Module3;
use App\Http\View\Module4;
use App\Http\View\Module6;
use App\Http\View\Module7;
use App\Http\View\Module8;
use App\Http\View\Module9;
use App\Http\View\ModuleGroup;
use App\Models\CmsChapterType;
use App\Models\CmsImage;
use App\Models\CmsMenu;
use Illuminate\Http\Request;

class IndexController
{
    public function index()
    {

        return Cms::create(function(\App\Http\View\Cms $cms){

            $title = "首页";
            $cms->title($title);

            $cms->setCss([
                "css/web/base.css",
                "css/web/tab.css",
            ]);

            $cms->setJs([
                "/js/jquery.js",
            ]);

            $container = $cms->container(function(Container $container) use ($cms){

                $container->addChild($cms->header());
                $container->addChild($cms->menu(CmsMenu::class));
                $container->addChild($cms->banner(CmsImage::class));


                $content = $cms->content();
                $container->addChild($content);

                $module_group = new ModuleGroup();
                $module_group->addChild(new Module1());
                $module_group->addChild(new Module2());
                $content->addChild($module_group);

                $module_group = new ModuleGroup();
                $module_group->addChild(new Module3());
                $content->addChild($module_group);

                $module_group = new ModuleGroup();
                $module_group->addChild(new Module4());
                $content->addChild($module_group);

                $module_group = new ModuleGroup();
                $module_group->addChild(new Module6());
                $module_group->addChild(new Module7());
                $content->addChild($module_group);

//                $module_group = new ModuleGroup();
//                $module_group->addChild(new Module8());
//                $content->addChild($module_group);

                $container->addChild(new Footer());

            });

        })->render();

//         view("web.framework", ["title" => "首页", "css" => $css]);
    }

    public function chapterList(Request $request)
    {
        return Cms::create(function(\App\Http\View\Cms $cms) use ($request){

            $type = $request->get("type", "");

            $keyword = $request->get("keyword", "");
            if(empty($keyword)){
                $type = CmsChapterType::find($type);
                $title = $type->name;
            }else{
                $title = "搜索";
            }

            $cms->title($title);

            $cms->setCss([
                "css/web/base.css",
                "css/web/tab.css",
            ]);

            $cms->setJs([
                "/js/jquery.js",
            ]);

            $container = $cms->container(function(Container $container) use ($cms, $type, $keyword, $title){

                $container->addChild($cms->header());
                $container->addChild($cms->menu(CmsMenu::class));
//                $container->addChild($cms->banner(CmsImage::class));


                $content = $cms->content();
                $container->addChild($content);

                $content->addChild(new BreadCrumb([
                    "首页",
                    $type->name
                ]));

                $where = [];
                if(!empty($type)){
                    $where[] = ["type", "=", "1"];
                }

                if(!empty($keyword)){
                    $where[] = ["title", "like", "%{$keyword}%"];
                }
                $module_group = new ModuleGroup();
                $module_group->addChild(new Module9());
                $module_group->addChild(new Module10($title, $where));
                $content->addChild($module_group);

                $container->addChild(new Footer());

            });

        })->render();
    }

    public function detail()
    {
        return Cms::create(function(\App\Http\View\Cms $cms){

            $title = "文章详情";
            $cms->title($title);

            $cms->setCss([
                "css/web/base.css",
                "css/web/tab.css",
            ]);

            $cms->setJs([
                "/js/jquery.js",
            ]);

            $container = $cms->container(function(Container $container) use ($cms){

                $container->addChild($cms->header());
                $container->addChild($cms->menu(CmsMenu::class));


                $content = $cms->content();
                $container->addChild($content);

                $content->addChild(new BreadCrumb([
                    "首页",
                ]));

                $module_group = new ModuleGroup();
                $module_group->addChild(new Module9());
                $module_group->addChild(new Module11(\request()->get("id")));
                $content->addChild($module_group);

                $container->addChild(new Footer());

            });

        })->render();
    }

    public function download()
    {
        return Cms::create(function(\App\Http\View\Cms $cms){

            $title = "下载专区";
            $cms->title($title);

            $cms->setCss([
                "css/web/base.css",
                "css/web/tab.css",
            ]);

            $cms->setJs([
                "/js/jquery.js",
            ]);

            $container = $cms->container(function(Container $container) use ($cms){

                $container->addChild($cms->header());
                $container->addChild($cms->menu(CmsMenu::class));


                $content = $cms->content();
                $container->addChild($content);

                $content->addChild(new BreadCrumb([
                    "首页","下载专区"
                ]));

                $module_group = new ModuleGroup();
                $module_group->addChild(new Module9());
                $module_group->addChild(new Module12());
                $content->addChild($module_group);

                $container->addChild(new Footer());

            });

        })->render();
    }


}