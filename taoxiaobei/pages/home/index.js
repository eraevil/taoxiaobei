// pages/home/index.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    swiper: [
      { image: '/images/swider1.png' },
      { image: '/images/swider2.png' }
    ],
    tab: [
      {
        id: 1,
        name: "美妆个护",
        image: '/images/tab1.png' 
      },
      {
        id: 2,
        name: "数码电子",
        image: '/images/tab2.png'
      },
      {
        id: 3,
        name: "生活用品",
        image: '/images/tab3.png'
      },
      {
        id: 4,
        name: "服装配饰",
        image: '/images/tab4.png'
      },
      {
        id: 5,
        name: "二手书籍",
        image: '/images/tab5.png'
      },
      {
        id: 6,
        name: "交通工具",
        image: '/images/tab6.png'
      },
      {
        id: 7,
        name: "票务卡券",
        image: '/images/tab7.png'
      },
      {
        id: 8,
        name: "其他类别",
        image: '/images/tab8.png'
      },
    ],
    productTab:[
      {
        name: "推荐",
        url: 'http://www.baidu.com'
      },
      {
        name: "数码",
        url: 'http://www.baidu.com'
      },
      {
        name: "美妆",
        url: 'http://www.baidu.com'
      },
      {
        name: "生活",
        url: 'http://www.baidu.com'
      },
      {
        name: "服饰",
        url: 'http://www.baidu.com'
      },
      {
        name: "书籍",
        url: 'http://www.baidu.com'
      },
      {
        name: "交通",
        url: 'http://www.baidu.com'
      },
      {
        name: "票券",
        url: 'http://www.baidu.com'
      },
      {
        name: "其他",
        url: 'http://www.baidu.com'
      }
    ],
    productList:[
      // 商品列表
    ],
    productNum: '', // 商品id
    msg: '',
    searchKey: '',
    indicatorDots: true,
    vertical: false,
    autoplay: true,
    interval: 5000,
    duration: 500
  },
  // 点击分类进行跳转
  category: function(e){
    let id = e.currentTarget.dataset['id'];
    // console.log("点击分类 " + id);
    wx.navigateTo({
      url: '/pages/home/goods/category/category?id=' + id
    })
  },

  // 获取输入框内容
  getInputValue: function(e){
    var that = this
    //console.log(e.detail.value)
    that.searchKey = e.detail.value
  },

  // 点击搜索商品
  search: function(e){
    var that = this
    // 跳转到搜索页面
    if(that.searchKey!=null)
      wx.navigateTo({
        url: '/pages/home/search/search?searchKey=' + that.searchKey
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

  // 商品页数
  pos: 1, 

  // 获取商品列表
  getGoodsList: function(page,page_size){
    // 加载中
    wx.showLoading({
      title: '加载中',
    });
    var that = this
    wx.request({
      url: 'https://www.taoxiaobei.cn/wx/goods/getGoodsList',
      header: {
        "content-type": "application/x-www-form-urlencoded"
      },
      data:{
        page: page,
        page_size: page_size
      },
      method:'POST',
      success:function(res){
        if(res.data.data.length == 0){
          // 无更多商品
          that.setData({
            msg: '· · · 您已成功触底 · · · '
          })
        }else{
          // 加载商品
          that.setData({
            productList: that.data.productList.concat(res.data.data) // 追加
          })
        }
        wx.hideLoading()
      },
      fail:function(){
        console.log("获取商品失败")
      }
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (page) {
    this.getGoodsList(1,20)
  },
  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {
    console.log("刷新页面")
    this.setData({
      productList: [],
    });
    this.getGoodsList(1,20)
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
    console.log("加载商品中...")
    this.pos++;
    this.getGoodsList(this.pos,20)

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})