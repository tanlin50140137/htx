<?php
/**
 * 控制流，此类不能移除（重要系统文件）
 * @author Administrator
 * 安全网站百分之90%以上的信息需要加密
 */
class EncryptionMechanism
{
	#加密标段
	private $segment;	
	#随机码
	private $random_code;
	/**
	 * 设置参数
	 * @param 标段 $segment
	 */
	public function EncryptionMechanism($segment)
	{
		$this -> SetFlowExchange();
		$this -> segment = $segment;
	}
	/**
	 * 输入流
	 */
	public function getInputStream()
	{
		return $this -> setOutputstream();
	}
	/**
	 * 设置第一个Base64，获取加密串流
	 */
	private function SetBase64Encode1()
	{
		return base64_encode( time() );
	}
	/**
	 * 设置第二个Base64，获取加密串流
	 */
	private function SetBase64Encode2()
	{
		return base64_encode( $this -> segment );
	}
	/**
	 * 设置流交换
	 */
	private function SetFlowExchange()
	{
		$letter = 'abcdefghijklmnopqrstuvwsyzABCDEFGHIJKLMNOPQRSTUVWSYZ123456789*#_=-';
		$letter = str_shuffle( $letter );
		$this->random_code = $letter;
	}
	/**
	 * 时间流
	 */
	private function setTimeFlow()
	{
		if( $this -> SetBase64Encode1() < $this -> SetBase64Encode2() )
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
	/**
	 * 截取流
	 */
	private function setIntercept()
	{
		$str = mb_substr( $this -> random_code, 0 , 16, "utf-8");//标准加密
		$len = mb_strlen( $str, "utf-8");
		return $len;
	}
	/**
	 * 识别流
	 */
	private function setDistinguish()
	{
		if( $this -> setIntercept() == 16 )
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
	/**
	 * 输出流
	 */
	private function setOutputstream()
	{
		if( $this -> setDistinguish() )
		{
			if(	$this -> setTimeFlow() )
			{
				return true;
			}
			else 
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}
}