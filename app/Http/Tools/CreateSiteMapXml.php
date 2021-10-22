<?php
/**
 * 通用生成sitemap文件
 * User: WXiangQian
 */

namespace App\Http\Tools;

class CreateSiteMapXml
{
    private $fileDir;

    public function __construct($filePath)
    {
        $this->mkFolder($filePath);
        $filePath = rtrim($filePath, '/') . '/';
        $this->fileDir = $filePath;
    }

    /**
     * 生成sitemaps 入口
     * @param array $ary msps数组
     * @param string $parent maps父标签
     * @param string $type maps类型
     * @return bool
     */
    public function addSiteMap($ary, $parent, $type)
    {
        if (empty($ary)) {
            return false;
        }

        $file = $this->fileDir . $type . ".xml";//获取最后更新文件
        $content = $this->maps($ary, $parent);      //生成片段 maps
        $f = $this->readFile($file);

        if (empty($f)) {
            //获取新文件头部
            $f = $this->typeMain();
        } else {
            $f = file_get_contents($file);
        }

        $nf = $this->strInsert($f, strpos($f, strrchr($f, '</')), $content);
        $this->writeFile($file, $nf);
    }

    public function ary2maps($idAry, $loc, $cf, $pri)
    {
        $lastmod = date('Y-m-d');
        $ary = array();

        foreach ($idAry as $id) {
            $ary[] = array(
                'loc' => $loc . $id,
                'lastmod' => $lastmod,
                'changefreq' => $cf,
                'priority' => $pri,
            );
        }

        return $ary;
    }

    //读取文件
    public function readFile($file)
    {
        if (empty($file)) {
            return false;
        }
        $f = file_exists($file);
        return $f ?: '';
    }

    //写入文件
    public function writeFile($fileName, $content)
    {
        //echo $content;
        file_put_contents($fileName, $content); //更新其值
    }

    /**
     * 通用 maps
     * @param array $ary
     * @param string $parent
     * @return string
     *
     */
    public function maps($ary, $parent): string
    {
        $str = '';
        if (is_array($ary)) {
            foreach ($ary as $mval) {
                $str .= "<$parent>\r\n";
                foreach ($mval as $key => $val) {
                    $str .= "    <$key>$val</$key>\r\n";
                }
                $str .= "</$parent>\r\n";
            }
        }
        return $str;
    }

    /**
     * 指定位置前插入字符串
     * @param string $str 原字符串
     * @param int $i 位置
     * @param string $substr 插入的字符串
     * @return string
     */
    public function strInsert($str, $i, $substr): string
    {
        $lstr = substr($str, 0, $i);
        $rstr = substr($str, $i, strlen($str));
        $newstr = ($lstr . $substr . $rstr);
        return $newstr;
    }

    // sitemap type
    public function typeMain(): string
    {
        $xml = "<?xml version='1.0' encoding='UTF-8'?>\r\n<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9' \r\n" .
            "xmlns:mobile='http://www.sitemaps.org/schemas/sitemap-mobile/1'> \r\n" . "</urlset>";

        return $xml;
    }

    // 检查目标文件夹是否存在，如果不存在则自动创建该目录
    public function mkFolder($path)
    {
        if (!is_readable($path)) {
//            is_file($path) or mkdir($path, 0777);
            is_file($path) || mkdir($path, 0777) || is_dir($path);
        }
    }

}
