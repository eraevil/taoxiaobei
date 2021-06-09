// pages/home/goodsdetail.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    goods_id: '',
    productInfo: [],
    showIntro: false,
    result: '',
    display: true,
    stars:[
      {
        flag:1,
        bgImg: "/images/star.png",
        bgfImg:"/images/f_star.png"
      },
      {
        flag: 1,
        bgImg: "/images/star.png",
        bgfImg:"/images/f_star.png"
      },
      {
        flag: 1,
        bgImg: "/images/star.png",
        bgfImg:"/images/f_star.png"
      },
      {
        flag: 1,
        bgImg: "/images/star.png",
        bgfImg:"/images/f_star.png"
      },
      {
        flag: 1,
        bgImg: "/images/star.png",
        bgfImg:"/images/f_star.png"
      }
    ],
  },

  // 加载商品详细信息
  getProductInfo: function(id){
    var that = this
    var app = getApp()
    wx.request({
      url: 'https://www.taoxiaobei.cn/wx/goods/getGoodsInfo',
      header: {
        "content-type": "application/x-www-form-urlencoded"
      },
      data:{
        goods_id: id,
        thr_session: app.globalData.userSessionKey,
      },
      method:'POST',
      success:function(res){
        that.setData({
          productInfo: res.data.data
        })
        if(res.data.data.goods_intro != null){
          that.setData({
            showIntro: true
          })
        }

        // 渲染评分数据
        var index=res.data.data.score;
        for(var i=0;i<=index;i++){
          var item = 'stars['+i+'].flag';
          that.setData({
            [item]:2
          })
        }
      },
      fail:function(){
        console.log("加载商品失败")
      }
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    if(options.display == null)options.display = ''
    var that = this
    this.getProductInfo(options.id)
    that.setData({
      goods_id: options.id
    })




  },

  // 去支付
  toPay: function(){
    wx.navigateTo({
      url: '/pages/home/goods/purchase/affirm/affirm?id=' + this.data.goods_id,
    })
  },
  
  // 预览图片
  preview(event) {
    // console.log(event.currentTarget.dataset.src)
    let currentUrl = event.currentTarget.dataset.src
    var img = []
    img[0] = this.data.productInfo.image
    wx.previewImage({
      current: currentUrl, // 当前显示图片的http链接
      urls: img// 需要预览的图片http链接列表
    })
  },

  // 评分
  score:function(e){
    var that=this;
    var app = getApp()
    for(var i=0;i<that.data.stars.length;i++){
      var allItem = 'stars['+i+'].flag';
      that.setData({
        [allItem]: 1
      })
    }
    var index=e.currentTarget.dataset.index;
    for(var i=0;i<=index;i++){
      var item = 'stars['+i+'].flag';
      that.setData({
        [item]:2
      })
    }

    console.log(index)
    wx.request({
      url: 'https://www.taoxiaobei.cn/wx/goods/score',
      data: {
        thr_session: app.globalData.userSessionKey,
        goods_id: that.data.goods_id,
        score: index+1,
      },
      method: 'POST',
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        wx.showToast({
          title: res.data.msg,
          icon: 'none'
        })
      }

    })
  },
})