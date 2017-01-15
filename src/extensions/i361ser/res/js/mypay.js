/*
 * @Copyright Copyright 2015, huasituo.com
 */
;(function () {
	setInit();
	function setInit()
	{
		var type = $('#J_payment_type li.current').find('a').data('val');
		var moneyhtml = '';
		var _code = code[type];
		for(var i in _code)
		{
			moneyhtml =moneyhtml + '<a href="" data-val="'+_code[i]+'" data-money="'+parseInt(_code[i])+'.00" title="">'+parseInt(_code[i])+'.00</a>';
		}
		$("#J_payment_money").html(moneyhtml);	//获取金额类型并选择第一个
		$("#J_payment_money a").eq('0').addClass('current');
		var rate = czlv[type] ? czlv[type] : 100;
		$("#J_recharge_rate").html(rate);
		setVal();
	}
	$('#J_payment_type').on('click', 'li', function(e){
		e.preventDefault();
		var _this=$(this),type=_this.find('a').data('val');
		$('#J_payment_type').find('li').removeClass('current');
		_this.addClass('current');
		var moneyhtml = '';
		var _code = code[type];
		for(var i in _code)
		{
			moneyhtml =moneyhtml + '<a href="" data-val="'+_code[i]+'" data-money="'+parseInt(_code[i])+'.00" title="">'+parseInt(_code[i])+'.00</a>';
		}
		$("#J_payment_money").html(moneyhtml);	//获取金额类型并选择第一个
		$("#J_payment_money a").eq('0').addClass('current');
		var rate = czlv[type] ? czlv[type] : 100;
		$("#J_recharge_rate").html(rate);
		setVal();
	});

	$('#J_payment_money').on('click', 'a', function(e){
		e.preventDefault();
		var _this=$(this),code=_this.find('a').data('val'),money=_this.find('a').data('money');
		$('#J_payment_money').find('a').removeClass('current');
		_this.addClass('current');
		setVal();
	});

	function setVal()
	{
		var type = $('#J_payment_type li.current').find('a').data('val');
		var code = $('#J_payment_money a.current').data('val');
		var money = $('#J_payment_money a.current').data('money');
		var rate = parseInt($('#J_recharge_rate').html());
		rate = rate ? rate : 100;
		$("#J_type").val(type);
		$("#J_code").val(type.toString()+code.toString());
		$("#J_money").val(money);
		var _money = Math.floor(money*rate/100);
		$("#J_recharge_count span").html(_money);
	}
	//提交
		var form = $('#J_recharge_form'),
			btn = form.find('button:submit');
		form.ajaxForm({
			dataType : 'json',
			beforeSubmit: function(){
				Wind.Util.ajaxBtnDisable(btn);
			},
			success : function(data){
				Wind.Util.ajaxBtnEnable(btn);
				if(data.state == 'success') {
	                //替换连接中&amp
	                //data.data.url = (data.data.url).replace(/&amp;/g,'&');
					//支付跳转
					//window.location.href = decodeURIComponent(data.data.url);//todo
					//window.location.href = data.referer;
					Wind.Util.resultTip({
						msg : '提交成功，处理中...',
						callback:function(){
							location.reload();
						}
					});
				}else if(data.state == 'fail'){
					//global.js
					Wind.Util.resultTip({
						error : true,
						msg : data.message
					});
				}
			}
		});
})();