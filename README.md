
rabbit-兔兔不良
============


特点
-------------------

* 基于多脏词匹配
* 脏词畸形纠正
* 基于官方大量脏词库，跟上社会动态。。。。
* 自定义脏词


安装
-------------------

下载符合自己机器的版本，运行即可


    $ ./rabbit


###支持的参数：


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
    -interval 设置重载脏词库的时间，单位是小时。默认 12个小时后自动重新加载一次。
#### -apikey 默认是一个测试key，如果要线上正式使用，请联系我拿apikey 加我微信: shajj1983


-------------------
API 测试后台
-------------------

####在线测试后台： http://tutusoft.net  可以先体验下。


目前内置了一个，基本的 脏词 增删改查的 管理后台。

启动rabbit服务后， http://你绑定的IP:端口

在里面你可以测试拦截效果。添加自定义脏词等。 可以通过这些API 开发你自己的 不良信息拦截监控后台。

-------------------
使用所要知道的
-------------------


* 黑名单和灰名单: 分别对应值: 1 和 2   比如： 撸撸射 是黑名单  苍井空是灰名单 
这样在拦截后，我会返回一个rate ，你就可以在代码做判断了。到底是拦截呢 还是事后在人工审查。


* 脏词库分两部分: 一份是官方的，一份是你自定义的。 内容过来了会先经过官方的核心脏词库过滤 然后到你自定义的。官方会不断更新脏词库。让你省心。以后我会开放脏词库，让大家来一起更新。

* 畸形纠正:  自定义脏词的时候，会有这个东西。默认的所有数字类型会转成 阿拉伯数字、繁体会转成简体。然后进行判断。但这样就会有误伤。 比如  血案+六四  那么当内容是： 血案呀，今天我被割到手指。在641寝室被割的。   那么这个内容就会被 血案+六四 给命中了。 所以  血案+六四 这个脏词添加的时候。  畸形纠正 我们选择值 2  进行关闭。 那么  六四在底层不会被转换成64. 那误伤率就大大减少了。

* 脏词类型：  违法、色情、政治敏感等。。。我定义了9个类型。您自己看着办。

* 脏词组合：  脏词是可以组合的。比如：  天猫招工+空闲+结算   目前最多匹配3个。  还支持几个内置联系类型： qq  phone  url   比如：  信用卡套现:phone  、 轻松赚钱+曰结:qq  等 



###记得重载脏词

修改完脏词后，记得进入  http://你绑定的IP:端口号 里面进行 脏词重新载入。否则不生效的哦。当然也支持 curl 操作


--------
API文档
--------
有了API，你就可以很方便的把服务接入你自己的项目了

###过滤API

POST /filter

参数: contents=蒙汗药

返回格式:json

具体返回值说明：
{"category":"9","categoryName":"违法信息","hit":"1","id":"38509","rate":"2","word":"蒙汗药"}

hit 是否命中, 0 否 1 是
category 脏词的分类id
categoryName 脏词所属分类名
id 脏词ID,利用这个ID就可以编辑脏词
rate黑名单或白名单，1 黑名单 2灰名单(自己review内容)
word 脏词

--------

###添加脏词

POST /create

参数:

category 分类id.请通过分类查询接口了解
word 脏词
rate 黑名单或灰名单. 1黑名单 2灰名单
correct 是否支持畸形纠正. 1 是 2否
返回格式:json

具体返回值说明： { "success": 1 }

--------

###删除脏词

DELETE /delete

参数: id=1

返回格式:json

具体返回值说明： { "success": 1 }


--------

###修改脏词

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

###脏词查询

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

###脏词分类

GET /category

参数:无

返回格式:json

[{"Category_id":1,"Category_name":"个性化"},{"Category_id":2,"Category_name":"低俗信息"},{"Category_id":3,"Category_name":"灌水信息"},{"Category_id":5,"Category_name":"政治敏感"},{"Category_id":6,"Category_name":"违约广告"},{"Category_id":7,"Category_name":"跨站追杀"},{"Category_id":8,"Category_name":"色情信息"},{"Category_id":9,"Category_name":"违法信息"},{"Category_id":10,"Category_name":"垃圾广告"}]


--------

###脏词重载

GET /reload

说明:添加或修改脏词后，重载才会生效

返回格式:json

具体返回值说明： { "success": 1 }


----------
压测数据
----------
硬件配置： 阿里云主机  CPU： 1核    内存： 1024 MB

压测的数据如下。内容共计：371个字数、817个字符：
```javascript
wrk.method = "POST"  
wrk.body  = "contents=原来，7日中午，张某夫妇带着儿子张峰（化名，现年2岁半）和女儿张娟（化名，现年1岁），来到位于银海区银滩镇龙潭村委会的姐夫刘某家吃饭。吃饭过程中，张峰和张娟被放在刘某房间玩耍，调皮的张峰在床头的夹层里翻出一包东西，他以为是零食，顺手抓了几颗放进嘴里。一旁的张娟见哥哥吃东西，她也爬过来拿着往嘴里塞两人的举动引起张某夫妇的注意。等走近一看，发现孩子们吃的东西竟是老鼠药。吓坏了的张某赶紧抱起儿子张峰，并从其嘴里抠出两粒老鼠药。“快送医院！”目睹眼前这一幕后，刘某急忙大喊。回过神来的夫妇，立即抱起两个孩子冲出门外，并拨打了120急救电话其间，由于担心路上被堵，刘某的妻子建议先把孩子送到附近派出所，再通过民警送往医医。当天下午，经过约一小时的急救和洗胃，张峰和张娟脱离了生命危险。随后，两人被安排在儿科儿童病房进行观察。8日下午，两人的各项身体指标，都已恢复正常医生提醒家长，蒙汗药是一种烈性毒药，千万不要放在小孩够得到的地方"

wrk.headers["Content-Type"] = "application/form-data"
```

跑下
```javascript
./wrk -t2 -c100 -d60s  --script=../post.lua http://10.161.171.74:9394/filter
```

两线程 100个连接  60秒 数据如下：
```javascript
Running 1m test @ http://10.16.17.74:9394/filter
  2 threads and 100 connections
  Thread Stats   Avg      Stdev     Max   +/- Stdev
    Latency    12.82ms   10.94ms  90.37ms   88.89%
    Req/Sec     4.63k     1.06k    6.85k    63.33%
  552582 requests in 1.00m, 70.62MB read
Requests/sec:   9207.21
Transfer/sec:      1.18MB
```

两线程 3000个连接 60秒  数据如下：
```javascript
Running 30s test @ http://10.16.17.74:9394/filter
  2 threads and 3000 connections
  Thread Stats   Avg      Stdev     Max   +/- Stdev
    Latency   314.93ms   97.50ms   1.82s    84.51%
    Req/Sec     4.48k     1.60k    7.33k    78.82%
  266017 requests in 30.04s, 33.99MB read
Requests/sec:   8854.35
Transfer/sec:      1.13MB
```

两线程 4000个连接 60秒  开始出现 timeout了...阿里云服务器也就如此了。数据如下：
```javascript
Running 30s test @ http://10.161.171.74:9394/filter
  2 threads and 4000 connections
  Thread Stats   Avg      Stdev     Max   +/- Stdev
    Latency   375.02ms  133.17ms   1.95s    78.31%
    Req/Sec     4.22k     1.68k    8.15k    69.59%
  249464 requests in 30.10s, 31.88MB read
  Socket errors: connect 0, read 871, write 0, timeout 136
Requests/sec:   8288.02
Transfer/sec:      1.06MB
```



换我的MAC 压测下。配置如下：Intel Core i5 1.6 GHz .内存 8 GB.  
```javascript
wrk -t8 -c100 -d60s  --script=./post.lua http://127.0.0.1:9394/filter
```
数据如下：
```javascript
Running 1m test @ http://127.0.0.1:9394/filter
  8 threads and 100 connections
  Thread Stats   Avg      Stdev     Max   +/- Stdev
    Latency     7.76ms   12.46ms 245.15ms   90.71%
    Req/Sec     2.62k   637.00    12.44k    79.64%
  1253342 requests in 1.00m, 198.42MB read
Requests/sec:  20853.38
Transfer/sec:      3.30MB
```

依然是我的MAC 测试下长内容性能和多脏词匹配。内容：4420个字数、9916个字符。脏词：网络+兼职+日入:qq 
```javascript
wrk -t8 -c100 -d60s  --script=./post.lua http://127.0.0.1:9394/filter
```
数据如下：
```javascript
Running 1m test @ http://127.0.0.1:9394/filter
  8 threads and 100 connections
  Thread Stats   Avg      Stdev     Max   +/- Stdev
    Latency    13.27ms   16.38ms 219.79ms   87.87%
    Req/Sec     1.33k   235.26     4.59k    73.11%
  636879 requests in 1.00m, 100.82MB read
Requests/sec:  10596.88
Transfer/sec:      1.68MB
```

----------
遇到问题了？
----------
加群： 243663452    找我 
