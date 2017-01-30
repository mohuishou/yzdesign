/**
 * @Copyright Copyright 2014, yhcms.com
 * @Descript: 前台-签到
 * @Author	: 89652519@qq.com
 * $Id$
 */

//打卡
;(function(){
	var punch_main_tip = $('#J_punch_main_tip'),		//我的打卡提示
		punch_wrap = $("#J_punch_widget"),//打卡元素
		punch_btn = punch_wrap.find('#J_punch_yunqian'),//打卡按钮
		mouseleave = false,
		p_lock = false;
		
	function isUndefined(variable) {
		return typeof variable == 'undefined' ? true : false;
	}
	punch_btn.on('click', function(e){
		//打卡
		e.preventDefault();
		var $this = $(this),qdxq=$('input[name="qdxq"]:checked'),qddiary=$('input[name="qddiary"]'),
		qdmode=$('input[name="qdmode"]:checked'),lrqddiary=$('select[name="lrqddiary"]'),qdcontent='';
		if(isUndefined(qdxq.val())||isNaN(qdxq.val())){
			punch_wrap.removeClass('punch_widget_disabled');
			Wind.Util.resultTip({
				error : true,
				msg : '你选择的心情不正确，请重新选择签到心情！'
			});
			return false;
		}
		if(qdmode.val()==1){
			if(!qddiary.val()||isUndefined(qddiary.val())){
				punch_wrap.removeClass('punch_widget_disabled');
				Wind.Util.resultTip({
					error : true,
					msg : '请输入点签到内容！'
				});
				return false;
			}qdcontent=qddiary.val();
		} else if(qdmode.val()==2){
			if(!lrqddiary.val()||isUndefined(lrqddiary.val())){
				punch_wrap.removeClass('punch_widget_disabled');
				Wind.Util.resultTip({
					error : true,
					msg : '请选择签到内容！'
				});
				return false;
			}qdcontent=lrqddiary.val();
		} else if(qdmode.val()==3){
			qdcontent='这人很懒，什么都没留下。';
		}
		if(punch_wrap.hasClass('punch_widget_disabled')) {
			return false;
		}else{
			punch_wrap.addClass('punch_widget_disabled');
			$.post($this.data('uri'), {qdxq:qdxq.val(),qdcontent:qdcontent,qdmode:qdmode.val()}, function(data){
				var d = data.data;
				if(data.state == 'success') {
					$this.text('连续'+ d.behaviornum +'天打卡');
					Wind.Util.resultTip({
						msg : '恭喜获得' +d.reward,
						follow : $this,
						callback:function(){
							location.reload();
						}
					});
				}else if(data.state == 'fail'){
					punch_wrap.removeClass('punch_widget_disabled');
					Wind.Util.resultTip({
						error : true,
						msg : data.message
					});
				}
			}, 'json').fail(function(){
				punch_wrap.removeClass('punch_widget_disabled');
			});
		}
	}).on('mouseenter', function(){
		var $this = $(this);
		mouseleave = false;
		if(punch_wrap.hasClass('punch_widget_disabled') || $this.data('role') == 'space') {
			return false;
		}else{
			if(punch_main_tip.children().length) {
				punch_main_tip.removeClass('dn');
			}else{
				$.post($this.data('tips'), function(data){
					if(data.state == 'success') {
						var punch_data = data.data;
						punch_main_tip.html('<div class="tips"><div class="core_arrow_top"><em></em><span></span></div>今天可领取'+ punch_data.todaycNum + punch_data.cUnit + 	
punch_data.cType +'<br />明天可领取'+ punch_data.tomorrowcNum + punch_data.cUnit + punch_data.cType +'<br />连续打卡每天增加'+ punch_data.step +'，上限'+ punch_data.max)
						if(!mouseleave){
								punch_main_tip.removeClass('dn');
						}
					}
				}, 'json');
			}
			
		}
	}).on('mouseleave', function(){
		mouseleave = true;
		punch_main_tip.addClass('dn');
	});
	
})();