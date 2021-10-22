<?php

namespace App\Console\Commands;

use App\Http\Tools\CreateSiteMapXml;
use Illuminate\Console\Command;

class CreateSiteMapXmlCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create_sitemap_xml';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成sitemap.xml文件';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $idAry = [
            117281450,
            110532521,
            105240627,
            105558534,
        ];
        $name = 'csdn';
        // 服务器目录地址
        $sitemaps = new CreateSiteMapXml('/Users/wxiangqian/my_project/laravel-api/sitemaps/');
        $videoAry = $sitemaps->ary2maps($idAry, 'https://wxiangqian.blog.csdn.net/article/details/', 'daily', '0.8');

        $sitemaps->addSiteMap($videoAry, 'url', $name);

    }
}
