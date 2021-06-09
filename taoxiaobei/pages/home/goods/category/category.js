// pages/home/category.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    curIndex: 0,
    navLeftItems:[
      {
        id: 0,
        name: "热门推荐"
      },
      {
        id: 1,
        name:"美妆个护"
      },
      {
        id: 2,
        name: "数码电子"
      },
      {
        id: 3,
        name: "生活用品"
      },
      {
        id: 4,
        name: "服装配饰"
      },
      {
        id: 5,
        name: "二手书籍"
      },
      {
        id: 6,
        name: "交通工具"
      },
      {
        id: 7,
        name: "票务卡券"
      },
      {
        id: 8,
        name: "其他类别"
      }
    ],
    productList: [
      // 商品列表
    ],
    msg: '正在加载更多 . . . '
  },

  // 商品页数
  pos: 1, 

  // 获取商品列表
  getGoodsList: function(page,page_size,id){
    var that = this
    wx.request({
      url: 'https://www.taoxiaobei.cn/wx/goods/getGoodsList',
      header: {
        "content-type": "application/x-www-form-urlencoded"
      },
      data:{
        page: page,
        page_size: page_size,
        category_id: id
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
            //productList: res.data.data //按页加载
            productList: that.data.productList.concat(res.data.data) // 追加
          })
        }
        //console.log(res.data.data)
      },
      fail:function(){
        console.log("获取商品失败")
      }
    })
  },

  /**
   * 生命周期函数--监听页面加载
   */
      onLoad: function (options) {
        let that = this;
        //console.log("pageIndex：", options);
        // 加载对应页面
        that.setData({
          curIndex: parseInt(options.id) || 0
        });

        // 清空其他数据
        that.setData({
          productList: [],
          msg: '正在加载更多 . . . '
        })

        // 当前不是推荐页，请求分类商品数据
        if(that.data.curIndex != 0){
          this.getGoodsList(1,20,that.data.curIndex)
        }else{
          that.setData({
            msg: '暂无推荐，敬请期待！'
          })
        }

        var app = getApp()
        app.sleep(500)
        that.setData({
          msg: '👉点击加载更多👈'
        })
      
      },
  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
      onPullDownRefresh: function () {
        console.log("刷新页面")
        this.setData({
          productList: [],
        });
        if(that.data.curIndex != 0){
          this.getGoodsList(that.pos,20,that.data.curIndex)
        }
      },
  /**
   * 页面上拉触底事件的处理函数
   */
      onReachBottom: function () {
        //console.log("下拉")
        var that = this
        that.pos++
        if(that.data.curIndex != 0){
          this.getGoodsList(that.pos,20,that.data.curIndex)
          that.setData({
            msg: '👉点击加载更多👈'
          })
        }
      },

      // 点击改变当前curIndex
      switchRightTab(e) {
        var that = this
        let index = parseInt(e.currentTarget.dataset.index); // 获取点击的tab id值
        this.setData({
          curIndex: index
        })
        console.log(that.data.curIndex)

        // 清空其他数据
        that.setData({
          productList: [],
          msg: '正在加载更多 . . . '
        })

        // 加载分类数据
        if(that.data.curIndex != 0){
          this.getGoodsList(1,20,that.data.curIndex)
        }else{
          that.setData({
            msg: '暂无推荐，敬请期待！'
          })
        }

        
      },

      // 点击跳转商品列表
      showListView(){
        console.log("点击跳转商品列表");
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

})