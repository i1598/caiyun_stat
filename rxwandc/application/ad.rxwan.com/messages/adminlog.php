<?php

defined('SYSPATH') or die('No direct script access.');

return array(
			"game"=>array(
				"create" => "创建游戏数据",
				"edit" => "修改游戏数据",
				"del"=>"删除游戏数据"
				),
			"opsys" =>array(
				"member"=>array(
					"create"=>"添加后台用户",
					"edit"=>"编辑后台用户" ,
					"del"=>" 删除后台用户"
				),
				"role"=>array(
					"create"=>"添加后台角色",
					"edit"=>"编辑后台角色" ,
					"del"=>" 删除后台角色"
				),
				"resource"=>array(
					"create"=>"添加菜单列表",
					"edit"=>"编辑菜单列表" ,
					"del"=>" 删除菜单列表"
				)
			),
			"operator"=>array(
				"create" => "创建运营商",
				"edit" => "修改运营商",
				"del"=>"删除运营商"
			),
			"mapping"=>array(
				"create" => "创建一条游戏运营信息",
				"edit" => "修改游戏运营信息",
				"del"=>"删除游戏运营信息"
			),
			"user"=>array(
				"lock"=>"锁定一条用户信息",
				"unlock"=>"解锁一条用户信息"		
			),
			"accounts"=>array(
				"receive"=>"接受返现"
			)
	);