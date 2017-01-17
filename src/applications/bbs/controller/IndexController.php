<?php
Wind::import('SRV:forum.srv.PwThreadList');

/**
 * 默认站点首页
 *
 * @author Jianmin Chen <sky_hold@163.com>
 * @license http://www.phpwind.com
 * @version $Id: IndexController.php 24758 2013-02-20 06:55:42Z jieyin $
 * @package forum
 */

class IndexController extends PwBaseController
{

    public $todayposts = 0;
    public $article = 0;

    /* (non-PHPdoc)
     * @see WindController::run()
     */
    public function run()
    {
        /* @var $forumDs PwForum */
        $forumDs = Wekit::load('forum.PwForum');
        $list = $forumDs->getCommonForumList(PwForum::FETCH_MAIN | PwForum::FETCH_STATISTICS);

        list($cateList, $forumList) = $this->_filterMap($list);
        $bbsinfo = Wekit::load('site.PwBbsinfo')->getInfo(1);

        $this->setOutput($cateList, 'cateList');
        $this->setOutput($forumList, 'forumList');
        $this->setOutput($this->todayposts, 'todayposts');
        $this->setOutput($this->article, 'article');
        $this->setOutput($bbsinfo, 'bbsinfo');
        $this->setTemplate('index_run');

        //seo设置
        Wind::import('SRV:seo.bo.PwSeoBo');
        $seoBo = PwSeoBo::getInstance();
        $seoBo->init('bbs', 'forumlist');
        Wekit::setV('seo', $seoBo);
    }

    /**
     * 过滤版块信息
     * 1、过滤掉不显示的版块
     *
     * @param array $list
     * @return array
     */
    private function _filterMap($list)
    {
        $cate = $forum = array();
        foreach ($list as $_key => $_item) {
            if (1 != $_item['isshow']) continue;
            $_item['manager'] = $this->_setManages(array_unique(explode(',', $_item['manager'])));
            if ($_item['parentid'] == 0) {
                $cate[$_key] = $_item;
                isset($forum[$_key]) || $forum[$_key] = array();
                $this->todayposts += $_item['todayposts'];
                $this->article += $_item['article'];
            } else {
                $forum[$_item['parentid']][$_key] = $_item;
            }
        }
        return array($cate, $forum);
    }

    /**
     * 设置版块的版主UID
     *
     * @param array $manage
     * @param array $userList
     * @return array
     */
    private function _setManages($manage)
    {
        $_manage = array();
        foreach ($manage as $_v) {
            if ($_v) $_manage[] = $_v;
        }
        return $_manage;
    }
}