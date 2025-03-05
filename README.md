# LINEBOT - あとでやるｃｈ

送信したトーク内容をそのまま定期的にリマインドしてくれるLINEBOTです

具体的には、「10分後」,「30分後」,「1時間後」,「3時間後」,「6時間後」, 以降は「次の日の朝６時」にリマインドされます


# DEMO

https://user-images.githubusercontent.com/28826492/153728875-5b8b42a7-b8f7-44a5-bb77-69acdf4a3a29.mp4

# Features

"リマインくん"という公式BOTの時間指定がいらなくなったものだと思ってください

# Usage

QRコードからお友達登録完了後、トークを送ることでタスクを登録できます

一覧で→スワイプをすると「あとで」と「こんど」を切り替えられます

一覧で←スワイプをするとタスクを完了します

「こんど」では時間を設定することが出来ます(10分単位)


# Note

自分用に同じBOTを作成したい場合
```bash
 herokuでアプリを作成
 gitをforkしてデプロイ
 
 heroku-postgresを追加
 DB_backup/database.dumpをデプロイ、あるいは同じような環境を作成
 
 LINELoginとMessagingAPIを作成
 LINELoginでLiffを追加しEndPointURLをherokuのアプリのURLにする
 MessagingAPIのWebHookURLをherokuのアプリのURL/linebotにする
 LineBotの自動返信機能を切る
 リッチメニューでliffと関連づけたボタンを作成する(ヘルプはどっちでもいい)
 
 herokuのConfig設定
 CHANNEL_ACCESS_TOKEN : MessagingAPIのChannelAccessToken(Long-lived)
 CHANNEL_SECRET : MessagingAPIのChannelSecret
 DATABASE_URL : postgresを追加した際に自動で追加される
 LOGIN_CHANNEL_ID : LINELoginのChannelID
 LOGIN_CHANNEL_SECRET : LNELoginのChannelSecret 
 
 heroku-schedulerを追加
 php ./public/php/scheduler.php　を一桁目０分ぴったりに追加する
 
 以上
```

# Author

* たまねぎ


# Readme template 

https://cpp-learning.com/readme/
