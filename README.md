# IR-Facebook-friends
Facebook API 1.0  friends recommendation and classifyer (now expired)
因為Facebook API政策的改變,  現今的API2.0已經無法得到使用者朋友的資訊, 固此App目前僅供演算法參考之用,
原App1.0的有效期限亦已過期.

## App1 : recommending friends by mining users’ implicit social network and interaction link analysis
首先，利用FACEBOOK登入取得使用者的Facebook ID、權限以及好友名單，接著利用FACEBOOK GRAPH API抓使用者的timeline最近的40篇動態，取得動態發布者以及對該使用者的動態留言或按讚的使用者ID(Story tag, message from, comments , likes)，將名單存為Hash List，並同時計算名單內使用者第一次的活躍分數。  
* 活躍分數 = 留言數 * 1 + 按讚數 * 0.5    ……(1)  
註：一則動態內多次留言只算一次
再來分別對名單中每個使用者，抓出動態時報上最近的40篇動態，取得對該使用者的動態留言或按讚的使用者ID，將名單存在另一個Hash List，然後對名單內使用者計算第二次的活躍分數，計算方式如公式(1)。
第一個Hash List (H1)中的使用者，分別都有第一次的活躍分數(F1)，也分別都會對應到另一個Hash List (H2)，H2中的使用者分別都有第二次的活躍分數(F2)。示意圖如下：

![Alt text](http://i.imgur.com/r3O1UXH.png "Hash")  
 H1裡不同使用者對應到的H2中，可能會出現重複的名單，活躍分數會一直累加，最後的活躍分數計算方式如下：  
 * 總分 += ( 0.7 + 0.2 * F1 ) * F2    ……(2)  
 最後依總分高低排序，然後比對好友名單，就可以知道你的交友圈中，朋友裡較活躍的人，和非朋友裡較活躍的人，後者就是你的潛在好友，你們之間可能有共通的話題、興趣和關注的好友，可以做為你們認識、聊天的接觸點。


## App2 : using clustering technique for friend classification
首先，要取得使用者的Facebook ID以及好友名單，將好友名單存在Hash List，每個好友都會對應到一個共同好友的Hash List(好友與使用者自己的共同好友名單)，示意圖如下：  

![Alt text](http://i.imgur.com/Z0kpFZA.png "common friend list")    
之後開始計算cosine similarity並且挑出值最大的兩個朋友。  
![Alt text](http://i.imgur.com/VQFuZTl.png "intersection") 

* 我們採用的是centroid-cluster，終止條件為：
(i)Max Cos < 0.2  (ii)Cluster數量< (好友數/20)
