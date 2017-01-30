<?php
defined('WEKIT_VERSION') or exit(403);

/**
 * 广告钩子
 * Class ADHook
 * @author mohuishou <1@lailin.xyz>
 */
class ADHook {

    private $open_type=[
        '1'=>"_blank",
        '2'=>"self"
    ];

    public function ads($id){
        $ad_type=$this->_typeDao()->get($id);
        $ads=$this->_itemDao()->getByTypeId($id);
        if(empty($ads)){
            return;
        }
        $func="createAdHtml".$ad_type['type'];

        foreach ($ads as $ad){
            echo $this->$func($ad,$ad_type);

            //弹窗类型广告只允许出现一个
            if($ad_type['type']==2||$ad_type['type']==3){
                return;
            }
        }


    }

    public function createAdHtml1($ad,$ad_type){
         return $html=<<<EOD
            <!-- 图片广告 -->
            <style>
                .m-ads-type-1 {
                    max-width: 100%;
                    max-height: 100%;
                    overflow: hidden;
                }
                
                .m-ads-type-1 a{
                    width: 100%;
                    display: block;
                }
                
                .m-ads-type-1 a img {
                    max-width: 100%;
                    max-height: 100%;
                }
            </style>
            <div class="m-ads-type-1">
                <a href="{$ad['link']}" target="{$this->open_type[$ad_type['open_type']]}">
                    <img src="{$ad['imgpath']}" alt="{$ad['title']}">
                </a>
            </div>
            <!-- 图片广告 end-->
EOD;

    }

    public function createAdHtml2($ad,$ad_type){
        return $html=<<<EOD
                    <!-- 中央弹窗 -->
                    <link rel="stylesheet" href="src/extensions/mADs/res/layer/skin/default/layer.css">
                    <script type="text/javascript">
                    Wind.js("src/extensions/mADs/res/jquery.min.js", "src/extensions/mADs/res/layer/layer.js", function() {
                        layer.open({
                            type: 1,
                            area: ['800px', '480px'],
                            title:"{$ad['window_title']}",
                            content: '<a style="max-width:100%;max-height:100%;overflow:hidden;display:block;" href="{$ad['link']}" target="{$this->open_type[$ad_type['open_type']]}"><img style="max-width:100%;" src="{$ad['imgpath']}" alt="{$ad['title']}"></a>'
                        });

                    });
                    </script>
                    <!-- 中央弹窗end -->
EOD;

    }

    public function createAdHtml3($ad,$ad_type){
        return $html=<<<EOD
                <!-- 右下角弹窗广告 -->
                    <link rel="stylesheet" href="src/extensions/mADs/res/layer/skin/default/layer.css">
                    <style>
                        .m-ads-type-3{
                            padding: 5px;
                        }
                        .m-ads-type-3 a{
                            color: #333;
                        }
                        .m-ads-type-3>div{
                            display: block;
                            height: 115px;
                            width: 100%;
                            border-bottom: 2px dotted #ddd;
                        }
                        .m-ads-type-3 .m-ads-title{
                            font-size: 15px;
                            font-weight: bold;
                            margin-bottom: 5px;
                        }
                        .m-ads-type-3 .m-ads-des{
                            margin-left: 10px;
                            float: left;
                            width: 180px;
                            height: 75px;
                            text-decoration: none;
	                        text-overflow: ellipsis;
                            overflow: hidden;
                        }
                        .m-ads-type-3 img{
                            width: 80px;
                            height: 80px;
                            float: left;
                            border-radius: 5px;
                        }
                        .m-ads-view{
                            margin-top: 5px;
                            width: 60px;
                            display: block;
                            float: right;
                        }
                    </style>
                    <script>
                        Wind.js("src/extensions/mADs/res/jquery.min.js", "src/extensions/mADs/res/layer/layer.js", function() {
                            layer.open({
                                type: 1,
                                area: ['300px', '200px'],
                                title:"{$ad['window_title']}",
                                shade: 0 ,
                                offset: 'rb', //右下角弹出
                                shadeClose: true, //点击遮罩关闭
                                content: '<div class="m-ads-type-3"><div><a href="{$ad['link']}" target="{$this->open_type[$ad_type['open_type']]}"><p class="m-ads-title">{$ad['title']}</p><div><img src="{$ad['imgpath']}" alt="{$ad['title']}" /><p class="m-ads-des">{$ad['description']}</p></div></a></div> <a class="m-ads-view" href="{$ad['link']}" target="{$this->open_type[$ad_type['open_type']]}">查看</a></div>'
                            });

                        });
                    </script>
                <!-- 右下角弹窗广告 end-->
EOD;
    }

    public function createAdHtml4($ad,$ad_type){
        return $html=<<<EOD
            <script>
                {$ad['script']}
            </script>
EOD;


    }

    private function _itemDao(){
        return Wekit::load('SRC:extensions.mADs.service.dao.App_MADs_ItemDao');
    }

    private function _typeDao(){
        return Wekit::load('SRC:extensions.mADs.service.dao.App_MADs_TypeDao');
    }
}

?>