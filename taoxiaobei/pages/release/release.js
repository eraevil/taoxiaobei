// pages/release/release.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    // 下拉框
    showSelected_category: false,
    options: [],
    old_new_grade: [{id:1, value: "全新"},{id:2, value: "九成新"},{id:3, value: "八五新"},{id:4,value: "七成新"},{id:5,value: "五成新"}],
    selected_category: {},
    selected_grade: {},

    // 最大字符数
     maxTextLen: 400,
    // 默认长度
    textLen: 0,

    
    // 数据
    goods_title: null,
    category_id: null,
    price: null,
    goods_intro: null,
    key_words: null,
    new_old_index: null,
    picPaths: '/images/cover.png',//图片网络路径
    checked: false,


    imgs: [],//本地图片地址数组

    // 禁用发布按钮
    banClick: false,
    

  },

  //添加上传图片
  chooseImageTap: function () {
    var that = this;
    wx.showActionSheet({
      itemList: ['从相册中选择', '拍照'],
      itemColor: "#00000",
      success: function (res) {
        if (!res.cancel) {
          if (res.tapIndex == 0) {
            that.chooseWxImage('album')
          } else if (res.tapIndex == 1) {
            that.chooseWxImage('camera')
          }
        }
      }
    })
  },
// 图片本地路径
chooseWxImage: function (type) {
  var that = this;
  var imgsPaths = that.data.imgs;
  wx.chooseImage({
    sizeType: ['original', 'compressed'],
    sourceType: [type],
    success: function (res) {
      console.log(res.tempFilePaths[0]);
      that.upImgs(res.tempFilePaths[0], 0) //调用上传方法
    }
  }) 
},
 //上传服务器
 upImgs: function (imgurl, index) {
  var that = this;
  wx.uploadFile({
    url: 'https://www.taoxiaobei.cn/wx/common/img_up',//
    filePath: imgurl,
    name: 'file',
    header: {
      'content-type': 'multipart/form-data'
    },
    formData: null,
    success: function (res) {
      //console.log(res) //接口返回网络路径
      var data = JSON.parse(res.data)
      that.setData({
        picPaths: 'https://www.taoxiaobei.cn/static/wx' + data.url
        
      })
      console.log(that.data.picPaths)
    }
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
    var that = this
    wx.request({
      url: 'https://www.taoxiaobei.cn/wx/goods/getCategoryInfo',
      header: {
        "content-type": "application/x-www-form-urlencoded"
      },
      method:'GET',
      success:function(res){
        //console.log(res.data.data)
        that.setData({
          options: res.data.data,
          showSelected_category: true
        })
        //console.log(that.data.options)
      }
    })
    setTimeout(function(){
      wx.hideToast()
     },800)


  },

 
  
  /** 获取输入值 */
  // 获取标题
  getTitle: function(e){
    this.setData({
      goods_title: e.detail.value
    })
    //console.log(this.data.goods_title)
  },
  // 获取关键词
  getKeyWords: function(e){
    this.setData({
      key_words: e.detail.value
    })
  },
  // 获取描述
  getWords(e) {
    let page = this;
    // 设置最大字符串长度(为-1时,则不限制)
    let maxTextLen = page.data.maxTextLen
    // 文本长度
    let textLen = e.detail.value.length
    page.setData({
      maxTextLen: maxTextLen,
      textLen: textLen,
      goods_intro: e.detail.value
    })
    console.log(this.data.textLen)
  },
   // 获取价格
   priceInput: function(e){
    var money;
    if (/^(\d?)+(\.\d{0,2})?$/.test(e.detail.value)) { //正则验证，小数点后不能大于两位数字
      money = e.detail.value;
    } else {
      money = parseFloat(e.detail.value).toFixed(2)
      // money = String(e.detail.value).substring(0, e.detail.value.length - 1);
    }
    if(money == 'NaN') money = 0
    this.setData({
      price: money,
    })
  },


  // 商品类别下拉操作
  change_category (e) {
    this.selectComponent('#select_grade').close()
    this.setData({
      selected_category: { ...e.detail }
    })
    if(this.data.selected_category.id != '000')
    {
      wx.showToast({
        title: `${this.data.selected_category.id} - ${this.data.selected_category.name}`,
        icon: 'success',
        duration: 1000
      })
      this.setData({
        // 获取选择的商品类别
        category_id: this.data.selected_category.id
      })
    }else{
      this.setData({
        selected_category: {}
      })
    }
    //console.log(this.data.selected_category)
  },
  close_category () {
    // 关闭select
    this.selectComponent('#select_category').close()
  },

  // 新旧程度下拉操作
  change_grade (e) {
    this.setData({
      selected_grade: { ...e.detail }
    })
    
    if(this.data.selected_grade.id != '000')
    {
      wx.showToast({
        title: `${this.data.selected_grade.name}`,
        icon: 'success',
        duration: 1000
      })
      this.setData({
        // 获取选择的商品类别
        new_old_index: this.data.selected_grade.name
      })
    }else{
      this.setData({
        selected_grade: {}
      })
    }
    console.log(this.data.selected_grade)
  },
  close_grade () {
    // 关闭select
    this.selectComponent('#select_grade').close()
  },

  // 弹出警告
  msg: function(msg){
    wx.showToast({
      title: msg,
      icon: 'none',
      duration: 1500
    })
  },

  // 上传商品
  upGoods: function(){
    var req = {}
    req.goods_title = this.data.goods_title
    req.category_id = this.data.category_id
    req.price = parseFloat(this.data.price).toFixed(2)
    req.goods_intro = this.data.goods_intro
    req.key_words = this.data.key_words
    req.new_old_index = this.data.new_old_index
    req.img = this.data.picPaths

    // 第三方session
    const app = getApp()
    console.log(app.globalData.userSessionKey)
    req.session = app.globalData.userSessionKey
    
    console.log(req)

    if(this.data.goods_title == null){
      this.msg("请输入标题")
      return
    }
    if(this.data.category_id == null){
      this.msg("请选择商品类别")
      return
    }
    if(this.data.price == null){
      this.msg("请输入价格")
      return
    }
    if(this.data.goods_intro == null){
      this.msg("请输入描述")
      return
    }
    if(this.data.key_words == null){
      this.msg("请输入关键词")
      return
    }
    if(this.data.new_old_index == null){
      this.msg("请选择新旧程度")
      return
    }
    if(this.data.picPaths == '/images/cover.png'){
      this.msg("请上传封面")
      return
    }
    if(this.data.checked == false){
      this.msg("请先阅读用户条约")
      return
    }

    // 点击过后禁用按钮，防止重复提交
    this.setData({
      banClick: true
    })

    // 请求
    wx.request({
      url: 'https://www.taoxiaobei.cn/wx/goods/release',
      header: {
        "content-type": "application/x-www-form-urlencoded"
      },
      method:'POST',
      data: req,
      success:function(res){
        // console.log(res.data)
        if(res.data.code == 200)
        {
          // 发布成功
          wx.showToast({
            title: '发布成功',
            icon: 'success',
            duration: 2000
          }) 
           wx.switchTab({
            url: 'index'
          })
        }else{
          wx.showToast({
            title: '失败',
            icon: 'fail',
            duration: 2000
          }) 
        }
      }
    })
  },

  changeBox: function(e){
    if(e.detail.value == ''){
      this.setData({
        checked: false
      })
    }else{
      this.setData({
        checked: true
      })
    }
  }
})