
rabbit-兔兔不良 是一个不良信息过滤服务
============


特点
=============

* 基于多脏词匹配
* 脏词畸形纠正
* 基于官方大量脏词库，跟上社会动态。。。。
* 自定义脏词


安装
============

下载符合自己机器的版本，运行即可


    $ ./rabbit


#支持的参数：


Usage of ./rabbit:

  -apikey string

        application apikey (default "sdeolkmddw")

  -host string

        bind address (default "127.0.0.1")

  -interval int

        auto reload time interval (default 12)

  -log string

        log file path (default "rabbit.log")

  -port int

        port the rabbit will listen on (default 9394)



其他默认就好。主要讲下这参数
    -interval 设置重载脏词库的时间，单位是小时。默认 12个小时后自动重新加载一次


API 测试后台
-------------------

目前内置了一个，基本的 脏词 增删改查的 管理后台。

启动rabbit服务后， http://你绑定的IP:端口/tool   

在里面你可以测试拦截效果。添加自定义脏词等。 可以通过这些API 开发你自己的 不良信息拦截监控后台。


使用所要知道的
-------------------


* 黑名单和灰名单: 分别对应值: 1 和 2   比如： 撸撸射 是黑名单  苍井空是灰名单 
这样在拦截后，我会返回一个rate ，你就可以在代码做判断了。到底是拦截呢 还是事后在人工审查。


* 脏词库分两部分: 一份是官方的，一份是你自定义的。 内容过来了会先经过官方的核心脏词库过滤 然后到你自定义的。官方会不断更新脏词库。让你省心。以后我会开放脏词库，让大家来一起更新。

* 畸形纠正:  自定义脏词的时候，会有这个东西。默认的所有数字类型会转成 阿拉伯数字、繁体会转成简体。然后进行判断。但这样就会有误伤。 比如  血案+六四  那么当内容是： 血案呀，今天我被割到手指。在641寝室被割的。   那么这个内容就会被 血案+六四 给命中了。 所以  血案+六四 这个脏词添加的时候。  畸形纠正 我们选择值 2  进行关闭。 那么  六四在底层不会被转换成64. 那误伤率就大大减少了。

* 脏词类型：  违法、色情、政治敏感等。。。我定义了9个类型。您自己看着办。

* 脏词组合：  脏词是可以组合的。比如：  天猫招工+空闲+结算   目前最多匹配3个。  还支持几个内置联系类型： qq  phone  url   比如：  信用卡套现:phone  、 轻松赚钱+曰结:qq  等 



###记得重载脏词

修改完脏词后，记得进入  http://你绑定的IP:端口号/tool  里面进行 脏词重新载入。否则不生效的哦。当然也支持 curl 操作



API文档
============

##过滤API

POST /filter

参数: contents=蒙汗药

返回格式:json

具体返回值说明：

hit 是否命中, 0 否 1 是
category 脏词的分类id
categoryName 脏词所属分类名
id 脏词ID,利用这个ID就可以编辑脏词
rate黑名单或白名单，1 黑名单 2灰名单(自己review内容)
word 脏词

--------

##添加脏词

POST /create

参数:

category 分类id.请通过分类查询接口了解
word 脏词
rate 黑名单或灰名单. 1黑名单 2灰名单
correct 是否支持畸形纠正. 1 是 2否
返回格式:json

具体返回值说明： { "success": 1 }

--------

##删除脏词

DELETE /delete

参数: id=1

返回格式:json

具体返回值说明： { "success": 1 }


--------

##修改脏词

PUT /revise

参数:

id脏词id.主键
category 分类id.请通过分类查询接口了解
word 脏词
rate 黑名单或灰名单. 1黑名单 2灰名单
correct 是否支持畸形纠正. 1 是 2 否
返回格式:json

具体返回值说明： { "success": 1 }

--------

##脏词查询

GET /query

参数:

id脏词id.主键
category 分类id.请通过分类查询接口了解
word 脏词
rate 黑名单或灰名单. 1黑名单 2灰名单
correct 是否支持畸形纠正. 1 是 2否
start 记录开始数(分页使用)
end 记录结束数(分页使用)
返回格式:json

具体返回值说明： [ { "Id": 9, "Category": 2, "CategoryName": "低俗信息", "Word": "我做你做不做", "Correct": 1, "Rate": 1 }, { "Id": 8, "Category": 2, "CategoryName": "低俗信息", "Word": "发问了你", "Correct": 1, "Rate": 1 } ]

--------

##脏词分类

GET /category

参数:无

返回格式:json

[{"Category_id":1,"Category_name":"个性化"},{"Category_id":2,"Category_name":"低俗信息"},{"Category_id":3,"Category_name":"灌水信息"},{"Category_id":5,"Category_name":"政治敏感"},{"Category_id":6,"Category_name":"违约广告"},{"Category_id":7,"Category_name":"跨站追杀"},{"Category_id":8,"Category_name":"色情信息"},{"Category_id":9,"Category_name":"违法信息"},{"Category_id":10,"Category_name":"垃圾广告"}]


--------

##脏词重载

GET /reload

说明:添加或修改脏词后，重载才会生效

返回格式:json

具体返回值说明： { "success": 1 }