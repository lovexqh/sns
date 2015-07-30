<?php
class UndercoverStatisticsModel extends Model {
	
	public function statistics() {
		$app_alias	 = getAppAlias('post');
		$roomDao = D('UndercoverRoom');
		$wordDao = D('UndercoverWord');

		return array(
			"房间总数"       => $roomDao->count(),
		    "词组总数"       => $wordDao->count(),
		);
	}
}