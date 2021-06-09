// pages/home/search.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    searchKey: '',
    productList:[
      // 商品列表
    ],
    productNum: '', // 商品id
    msg: ''
  },

  // 商品页数
  pos: 1,

  // 获取输入框内容
  getInputValue: function(e){
    var that = this
    if(e.detail.value != '')
      //console.log(e.detail)
      that.data.searchKey = e.detail.value
  },
  
  // 点击搜索按钮
  searchClick: function(){
    var that = this
    that.setData({
      productList: []
    })
    this.search(1,20)
     
  },

  // 发送搜索请求
  search: function(page,page_size){
    // 加载中
    wx.showToast({
      title: '加载中',
      icon: 'loading',
      duration: 10000
     })
     setTimeout(function(){
      wx.hideToast()
     },300)

    var that = this
    wx.request({
      url: 'https://www.taoxiaobei.cn/wx/goods/searchGoodsList',
      header: {
        "content-type": "application/x-www-form-urlencoded"
      },
      data:{
        page: page,
        page_size: page_size,
        key: that.data.searchKey
      },
      method:'POST',
      success:function(res){
        //console.log(res.data)
        if(res.data.data.length == 0){
          // 无更多商品
          that.setData({
            msg: '· · · 您已成功触底 · · · '
          })
        }else{
          // 加载商品
          that.setData({
            //productList: res.data.data //按页加载
            productList: that.data.productList.concat(res.data.data) // 追加
          })
        }
        //console.log(res.data.data)
      },
      fail:function(){
        console.log("搜索商品失败")
      }
    })
  },

  // 点击商品详情
  clickProduct: function(e){
    var that = this
    that.setData({
      productNum: e.currentTarget.dataset.id
    })
    //console.log("点击商品" + that.data.productNum)
    // 跳转到商品详情页
    wx.navigateTo({
      url: '/pages/home/goods/goodsdetail/goodsdetail?id=' + that.data.productNum
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    // 加载中
    wx.showToast({
      title: '加载中',
      icon: 'loading',
      duration: 10000
     })
     setTimeout(function(){
      wx.hideToast()
     },300)

    var that = this
    this.setData({
      searchKey: options.searchKey
    })
    // 搜索请求
    this.search(1,20)

  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {

  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
    console.log("加载商品中...")
    this.pos++;
    this.search(this.pos,20)
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})