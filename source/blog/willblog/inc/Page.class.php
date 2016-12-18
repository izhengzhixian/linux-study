<?php
/** 
 *	配CSS样式：page.css
 *	File name: Page.class.php 
 *	Created by: Wends QQ24203741
 *	Contact: wds00136@163.com,http://www.113344.com
 *	Last modified: 2012-08-13 Ver: 2.0
 */
class Page {
	private $totalRows;
	private $listRows;
	private $nowPage;
	private $pageUrl;
	private $totalPage;
	private $firstRows;
	private $rollPage = 5;
	private $config = array('header'=>'条记录','prev'=>'上一页','next'=>'下一页','first'=> '首页','last'=>'尾页','theme'=>0); //theme为1时为li输出请添加CSS样式page.css
	private $html = ' [%totalRows% %header%] [%nowPage%/%totalPage% 页] %first% %upPage% %prePage%  %linkPage%  %nextPage% %downPage% %end%'; //新增页码转跳输入框 %input%

	public function __construct($totalRows, $listRows, $nowPage, $pageUrl) {
		$this->totalRows	= (int) $totalRows;
		$this->listRows		= $this->getInt($listRows);
		$this->nowPage		= $this->getInt($nowPage);
		$this->pageUrl		= strip_tags($pageUrl);
		$this->totalPage	= ceil($this->totalRows/$this->listRows); 	
		if ($this->nowPage > $this->totalPage) {
			$this->nowPage = $this->totalPage;
		}
		$this->firstRows	= $this->listRows * ($this->nowPage - 1);		
	}

	private function getInt($number) {
		$count = intval($number);
		return ($count > 0)? $count : 1;
	}

	public function getLimit() {
		return $this->firstRows.','.$this->listRows;
	}

	public function setConfig($property, $value) {
		if (array_key_exists($property, $this->config)) {
			$this->config[$property] = strip_tags($value);
		} elseif ($property == 'rollPage') {
			$this->rollPage = $this->getInt($value);
		}
	}

	public function setHtml($html) {
		$this->html = $html;
	}

	private function getUrl($pageNum) {
		return str_replace('{page}', $pageNum, $this->pageUrl);
	}

	public function getHtml($id = 'page') {
		if (($this->totalRows == 0) || ($this->totalRows <= $this->listRows)) {
			return '';
		}
		$coolPages		= ceil($this->totalPage/$this->rollPage);
		$nowCoolPage	= ceil($this->nowPage/$this->rollPage);
		//prev next
        $upRow   = $this->nowPage - 1;
        $downRow = $this->nowPage + 1;
        if ($upRow > 0){
            $upPage = '[<a href="'.$this->getUrl($upRow).'">'.$this->config['prev'].'</a>]';
        } else {
            $upPage = '['.$this->config['prev'].']';
        }
        if ($downRow <= $this->totalPage){
			$downPage = '[<a href="'.$this->getUrl($downRow).'">'.$this->config['next'].'</a>]';
        } else {
            $downPage = '['.$this->config['next'].']';
        }
		// << < > >>
        if ($nowCoolPage == 1){
			$prePage	= '';
			if ($this->nowPage == 1) {
				$theFirst = '['.$this->config['first'].']';
			} else {
				$theFirst = '[<a href="'.$this->getUrl(1).'">'.$this->config['first'].'</a>]';
			}
        } else {
            $preRow		=  $this->nowPage - $this->rollPage;
			$prePage	= '[<a href="'.$this->getUrl($preRow).'" title="上'.$this->rollPage.'页">&lt;&lt</a>]';
			$theFirst	= '[<a href="'.$this->getUrl(1).'">'.$this->config['first'].'</a>]';
        }
        if ($nowCoolPage == $coolPages){
            $nextPage	= '';
			if ($this->nowPage == $this->totalPage) {
				$theEnd = '['.$this->config['last'].']';
			} else {
				$theEnd	= '[<a href="'.$this->getUrl($this->totalPage).'">'.$this->config['last'].'</a>]';
			}
        } else {
            $nextRow	= $this->nowPage + $this->rollPage;
            $nextPage	= '[<a href="'.$this->getUrl($nextRow).'" title="下'.$this->rollPage.'页">&gt;&gt</a>]';
            $theEnd		= '[<a href="'.$this->getUrl($this->totalPage).'">'.$this->config['last'].'</a>]';
        }
        // 1 2 3 4 5 6
        $linkPage = '';
        for($i = 1; $i <= $this->rollPage; $i ++) {
            $page = ($nowCoolPage - 1) * $this->rollPage + $i;
            if ($page != $this->nowPage){
                if( $page <= $this->totalPage) {
					$linkPage .= ' [<a href="'.$this->getUrl($page).'">'.$page.'</a>] ';
                }else{
                    break;
                }
            } else {
                if($this->totalPage != 1){
                    $linkPage .= ' [<span class="current">'.$page.'</span>] ';
                }
            }
        }
	
		$input = "[&nbsp;<input type=\"text\" value=\"{$this->nowPage}\" onkeydown=\"javascript: if(event.keyCode==13){ location='{$this->getUrl("'+this.value+'")}';return false;}\" title=\"页码跳转\" />&nbsp;]";

		$html = str_replace(
			array('%totalRows%','%header%','%nowPage%','%totalPage%','%upPage%','%downPage%','%first%',  '%prePage%','%linkPage%','%nextPage%','%end%','%input%'),
			array($this->totalRows,$this->config['header'],$this->nowPage,$this->totalPage,$upPage,$downPage,$theFirst,$prePage,$linkPage,$nextPage,$theEnd, $input),
			$this->html);
		if ($this->config['theme'] == 1) {
			$html = str_replace(array('[',']'),array('<li>','</li>'),$html);
			$html = '<ul>'.$html.'</ul></div><div style="clear:both">';
		} 
		$html = str_replace(array('[',']'),'&nbsp;',$html);
		return '<div id="'.$id.'" class="pages">'.$html.'</div>';
	}
}
?>
