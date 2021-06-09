// pages/me/goods/goodslist.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    goods_list: [{
      img: "/images/cover.png",
      goods_title: "",
      price: "",
      add_time: "",
      flag_image: "",
    }]
    
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this
    // 加载中
    wx.showLoading({
      title: '加载中',
    });

    var app = getApp()
    wx.request({
      url: 'https://www.taoxiaobei.cn/wx/goods/getGoodsListByStatus',
      method: 'POST',
      header: {
        "content-type": "application/x-www-form-urlencoded"
      },
      data: {
        thr_session: app.globalData.userSessionKey,
        status: options.status,
      },
      success: function(res){
        // console.log(res.data.data)
        that.setData({
          goods_list: res.data.data
        })

        if(that.data.goods_list.length == 0){
          wx.hideLoading()

          wx.showToast({
            title: '无数据',
            icon: 'none',
            duration: 3000
          })

          setTimeout(function () {
            wx.navigateBack({
              delta: -1,
            }) 
           }, 3000) //延迟时间 这里是1秒

        }
        var img_url = ''
        for(var i = 0; i < that.data.goods_list.length;i++){
          switch(that.data.goods_list[i].goods_status){
            case 0: img_url = "/images/releasing.png";break;
            case 1: img_url = "/images/doing.png";break;
            case 2: img_url = "/images/paied.png";break;
            case 3: img_url = "/images/finished.png";break;
            case 4: img_url = "/images/rejected.png";break;
          }

          var elem = 'goods_list[' + i + '].flag_image'
          that.setData({
            [elem]: img_url
          })
        }
        // console.log(that.data.goods_list)

        wx.hideLoading()
      }
    })
  },

  goDetails: function(e){
    var id = e.currentTarget.dataset.id
    wx.navigateTo({
      url: '/pages/home/goods/goodsdetail/goodsdetail?id=' + id + '&display=false'
    })
  }
})