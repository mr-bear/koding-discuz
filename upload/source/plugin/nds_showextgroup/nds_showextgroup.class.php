<?php
/*  ShowExtgroup 
 *  Plugin FOR Discuz! X2 
 *	Copyright (c) 2009-2010 WWW.NWDS.CN | NDS.西域数码工作室
 *	$Id: nds_showextgroup.class.php V1.0 20100801 SINGCEE $
 */

if(!defined('IN_DISCUZ')) {
     exit('Access Denied');
}

class plugin_nds_showextgroup {
     function plugin_nds_showextgroup() {
          global $_G;
          $this->showextgroupicon = $_G['cache']['plugin']['nds_showextgroup']['showextgroupicon'];
          $this->showextgroupsite = $_G['cache']['plugin']['nds_showextgroup']['showextgroupsite'];
     }
}
class plugin_nds_showextgroup_forum extends plugin_nds_showextgroup {
     function nds_showextgroup_go() {
          global $_G,$postlist;
          $ndsreturneg = array();
          if(empty($postlist) || !is_array($postlist)) return $ndsreturneg;
          $postuid = '';
          $extgroupidstr ='';
          foreach ($postlist as $pid =>$post) {
               $postuid = $post['authorid'];
               $extgroupidstr = DB::fetch_first("SELECT extgroupids FROM ".DB::table('common_member')." WHERE uid = '$postuid' ");
               if (empty($extgroupidstr)) return $ndsreturneg;
               $extgrouptitles = '';
               $extgrouptitle ='';
               $extgroupids = explode("\t",$extgroupidstr['extgroupids']);
               foreach ( $extgroupids as $extgroupid) {
                    if ($this->showextgroupicon == 1) {
                         $extgrouptitle = $_G['cache']['usergroups'][$extgroupid]['grouptitle'];
                         $extgrouptitles = $extgrouptitles.'<p><em>'.$extgrouptitle.'</em></p>';
                    }
                    elseif($this->showextgroupicon == 2) {
                         if(substr($_G['cache']['usergroups'][$extgroupid]['icon'], 0, 5) == 'http:') {
                              $extgrouptitle = '<p><img src="'.$_G['cache']['usergroups'][$extgroupid]['icon'].'" align="absmiddle" /></p>';
                         } elseif(!empty($_G['cache']['usergroups'][$extgroupid]['icon'])) {
                              $extgrouptitle = '<p><img src="data/attachment/common/'.$_G['cache']['usergroups'][$extgroupid]['icon'].'" align="absmiddle" /></p> ';
                         }else {
                              $extgrouptitle = '<p><em>'.$_G['cache']['usergroups'][$extgroupid]['grouptitle'].'</em></p>';
                         }
                         $extgrouptitles = $extgrouptitles.$extgrouptitle;
                    }
               }
               $ndsreturneg[] = $extgrouptitles;
          }
          return $ndsreturneg;
     }

     function viewthread_sidetop_output() {
          if ($this->showextgroupsite <> '1' ) return array();
          return  $this->nds_showextgroup_go();
     }

     function viewthread_sidebottom_output() {
          if ($this->showextgroupsite <> '2' ) return array();
          return  $this->nds_showextgroup_go();
     }

}         

?>