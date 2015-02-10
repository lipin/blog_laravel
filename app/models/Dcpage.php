<?php 
class Dcpage extends Illuminate\Pagination\Presenter{
    /**
  * 获取分页可用连接HTML
  *
  * @param  string  $url
  * @param  int  $page
  * @param  string  $rel
  * @return string
  */
 public function getPageLinkWrapper($url, $page, $rel = null)
 {
  $rel = is_null($rel) ? '' : ' rel="'.$rel.'"';
  return '<a href="'.$url.'"'.$rel.'>'.$page.'</a>';
 }
 /**
  * 获取失效页HTML
  *
  * @param  string  $text
  * @return string
  */
 public function getDisabledTextWrapper($text)
 {
  return '<a class="disabled">'.$text.'</a>';
 }
 /**
  * 获取当前页HTML
  *
  * @param  string  $text
  * @return string
  */
 public function getActivePageWrapper($text)
 {
  return '<a class="active">'.$text.'</a>';
 } 
    /**
  * 获取分页HTML字符串
  * $minPageNum 最小省略分页个数
  * @return string
  */
 public function pageString($minPageNum=13)
 {
  if ($this->lastPage < $minPageNum)
  {
   $content = $this->getPageRange(1, $this->lastPage);
  }
  else
  {
   $content = $this->getPageSlider();
  }
  return $this->getHome().$this->getPrevious('上一页').'<span>'.$content.'</span>'.$this->getNext('下一页').$this->getEnd();
 }
    /**
  * 获取首页HTML
  *
  * @param  string  $text
  * @return string
  */
 public function getHome($text = '首页')
 {
  if ($this->currentPage <= 1)
  {
   return $this->getDisabledTextWrapper($text);
  }
  else
  {
   $url = $this->paginator->getUrl(1);
   return $this->getPageLinkWrapper($url, $text, 'prev');
  }
 }
    /**
  * 获取尾页HTML
  *
  * @param  string  $text
  * @return string
  */
 public function getEnd($text = '尾页')
 {
  if ($this->currentPage >= $this->lastPage)
  {
   return $this->getDisabledTextWrapper($text);
  }
  else
  {
   $url = $this->paginator->getUrl($this->lastPage);
   return $this->getPageLinkWrapper($url, $text, 'next');
  }
 }
}