from multiprocessing import Pool
import requests,re,time,os,random
def dowmpicture(url,i):

	header={'referer':'https://www.pixiv.net'}
	r = requests.get(url,headers=header)
	if r.status_code != 200:
		url=re.sub('jpg','png',url)
		r = requests.get(url,headers=header)
		print(url)
		with open('./%d.png'%i,'wb') as f:
			f.write(r.content)
	else:
		print(url)
		with open('./%d.jpg'%i,'wb') as f:
			f.write(r.content)			
	print('%d下载完成'%i)
	
if __name__=='__main__':
	r = requests.get('https://www.pixiv.net/ranking_log.php?mode=daily&content=all&date=201912')
	urls = re.findall(r'data-src="(.*)"src="',r.text,0)
	i=0
	p = Pool()
	for url in urls:
		url=re.sub('/c/128x128/img-master','/img-original',url)
		url=re.sub('/c/128x128/custom-thumb','/img-original',url)
		url=re.sub('_custom1200','',url)
		url=re.sub('_square1200','',url)
		i=i+1
		p.apply_async(dowmpicture, args=(url,i,))
	print('开始下载...')
	p.close()
	p.join()
	print('下载结束')
