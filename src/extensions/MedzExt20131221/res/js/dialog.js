Wind.use('jquery', 'global', function(){
	var m_lock = false;
	$('a.Medz_ext20131221').on('click', function(e){
		e.preventDefault();
		if(m_lock) {
			return;
		}
		m_lock = true;

		Wind.Util.ajaxMaskShow();
		$.post($(this).data('uri'), function(data){
			m_lock = false;
			Wind.Util.ajaxMaskRemove();
			if(Wind.Util.ajaxTempError(data)) {
				return;
			}
			Wind.use('dialog', function(){
				Wind.dialog.html(data, {
					id: 'Medz_ext20131221_pop',
					title: '帖子聚合',
					isMask: false,
					isDrag: true,
					callback: function(){
						//提交
						var form = $('#Medz_report_form'),
							btn = form.find('button:submit');
						form.on('submit', function(e){
							e.preventDefault();
							Wind.use('ajaxForm', function(){
								form.ajaxSubmit({
									dataType: 'json',
									beforeSubmit: function(){
										Wind.Util.ajaxBtnDisable(btn);
									},
									success: function(data){
										Wind.Util.ajaxBtnEnable(btn);
										if(data.state == 'success') {
											Wind.Util.formBtnTips({
												wrap: btn.parent(),
												msg: data.message,
												callback: function(){
													location.reload();
												}
											});
										}else{
											Wind.Util.formBtnTips({
												error: true,
												wrap: btn.parent(),
												msg: data.message
											});
										}
									}
								});
							});

						});
					}
				});
			});
		}, 'html')
		.fail(function(){
			m_lock = false;
		});
	});
});