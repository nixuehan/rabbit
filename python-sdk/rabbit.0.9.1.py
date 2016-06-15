# -*- coding: utf-8 -*-
"""
https://github.com/nixuehan/rabbit
"""
__author__ = '逆雪寒'
__version__ = '0.9.1'

try:
	import requests
except ImportError,msg:
	raise ImportError("Please install python requests. pip install requests")


class RabbitException(Exception):
	pass


class Rabbit(object):
	"""doc:  https://github.com/nixuehan/rabbit. """

	YES,NO = 1,0

	def __init__(self,host,port,timeout=2,trace=0):
		self.host,self.timeout,self.trace = "http://" + host + ":" + str(port) + "/",timeout,trace

	def __do(self,do):
		return self.host + do

	def __trace(self,msg):
		if self.trace == self.YES:
			print msg
		pass

	def filter(self,contents):
		"""
		过滤内容
		"""
		try:
			result = requests.post(self.__do('filter'),params={'contents':contents},timeout=self.timeout)
			data = result.json()
			if ("success" in data) and (data["success"] == self.NO):
				self.__trace(data)
				return False
			return data

		except Exception,msg:
			raise RabbitException(msg)


	def porn(self,filePath):
		"""
		过滤色图 score 建议：65 - 85判断为性感 85以上为色情
		"""
		try:
			result = requests.post(self.__do('porn'),params={'file':filePath},timeout=self.timeout)
			data = result.json()
			if ("success" in data) and (data["success"] == self.NO):
				self.__trace(data)
				return False
			return data['score']

		except Exception,msg:
			raise RabbitException(msg)


	def delete(self,id):
		"""
		删除脏词
		"""
		try:
			result = requests.delete(self.__do('delete'),params={'id':id},timeout=self.timeout)
			data = result.json()
			if ("success" in data) and (data["success"] == self.NO):
				return False
			return data.json()
		except Exception,msg:
			raise RabbitException(msg)

	def create(self,info):
		"""
		创建脏词

		@param dict
		string  word 脏词 
		int 	category 脏词分类
		int 	rate 黑名单OR灰名单 1 or 2
		int 	correct 是否畸形纠正  1  or 2
		"""
		try:
			result = requests.post(self.__do('create'),params=info,timeout=self.timeout)
			data = result.json()
			if ("success" in data) and (data["success"] == self.NO):
				self.__trace(data)
				return False
			return data

		except Exception,msg:
			raise RabbitException(msg)		

	def revise(self,info):
		"""
		修改脏词

		@param dict
		int 	id 脏词id
		string  word 脏词
		int 	category 脏词分类
		int 	rate 黑名单OR灰名单 1 or 2
		int 	correct 是否畸形纠正  1  or 2
		"""
		try:
			result = requests.put(self.__do('revise'),params=info,timeout=self.timeout)
			data = result.json()
			if ("success" in data) and (data["success"] == self.NO):
				self.__trace(data)
				return False
			else:
				return True
			return data

		except Exception,msg:
			raise RabbitException(msg)

	def query(self,info):
		"""
		查询脏词信息

		@param dict
		int 	id 脏词id
		string  word 脏词
		int 	category 脏词分类
		int 	rate 黑名单OR灰名单 1 or 2
		int 	correct 是否畸形纠正  1  or 2
		"""
		try:
			result = requests.get(self.__do('query'),params=info,timeout=self.timeout)
			data = result.json()
			if ("success" in data) and (data["success"] == self.NO):
				self.__trace(data)
				return False
			return data

		except Exception,msg:
			raise RabbitException(msg)

	def reload(self):
		"""
		脏词重载
		"""
		try:
			result = requests.get(self.__do('reload'),timeout=self.timeout)
			data = result.json()
			if ("success" in data) and (data["success"] == self.NO):
				return False
			return True
		except Exception,msg:
			raise RabbitException(msg)


rabbit = Rabbit(host='127.0.0.1',port=9394)

try:
	score = rabbit.porn("https://ss0.bdstatic.com/94oimg?image&quality=100&size=b4000_4000&sec=1465993408&di=db6c2337ac2ffe0cab3a6410e987098c&src=http://t1.mmonly.cc/uploads/allimg/20150727/h2pg440kruf.jpg")
except RabbitException,msg:
	score = False

print score
# print rabbit.query({'category':2,'rate':2})

# result = rabbit.filter("我吃蒙汗药")
# if(result['hit']):
# 	print 'hit'
# else:
# 	print 'no'