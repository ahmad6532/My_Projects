import React from 'react';
import { Slide } from 'react-slideshow-image';
import 'react-slideshow-image/dist/styles.css'

export function SpringSlider1 (){
const spanStyle = {
  padding: '20px',
  background: '#efefef',
  color: '#000000'
}

const divStyle = {
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
  backgroundSize: 'cover',
  height: '500px'
}
const slideImages = [
  {
    url: 'https://www.teahub.io/photos/full/227-2272082_awesome-sakura-tree-free-background-id-background-cherry.jpg',
    
  },
  {
    url: 'https://images.squarespace-cdn.com/content/v1/5d4428ffacde33000191b0ff/1616533279247-WPWWWNRWN1SKK0O67OTL/spring-sakura-flower-spring-wallpaper-preview.jpg?format=1000w',
   
  },
  {
    url: 'https://wallpaperaccess.com/full/11453.jpg',
   
  },
  {
    url: 'https://www.designyourway.net/blog/wp-content/uploads/2020/04/Blossom-Branch-Spring-HD-Wallpaper-%E2%80%93-Natural-bouquet.jpg',
   
  },
  
];


    return (
      <div className="slide-container">
        <Slide autoplayInterval={1000}>
         {slideImages.map((slideImage, index)=> (
            <div key={index}>
              <div style={{ ...divStyle, 'backgroundImage': `url(${slideImage.url})` }}>
              
              </div>
            </div>
          ))} 
        </Slide>
      </div>
    )

}
export function SpringSlider2 (){
  const spanStyle = {
    padding: '20px',
    background: '#efefef',
    color: '#000000'
  }
  
  const divStyle = {
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    backgroundSize: 'cover',
    height: '500px'
  }
  const slideImages = [
    {
      url: 'https://s2.best-wallpaper.net/wallpaper/1920x1080/1712/Yellow-daffodils-flowers-tree-grass_1920x1080.jpg',
      
    },
    
    {
      url: 'https://wallpaperaccess.com/full/5517452.jpg',
      
    },
    {
      url: 'https://w0.peakpx.com/wallpaper/1023/514/HD-wallpaper-daffodils-white-spring-flowers-background-with-daffodils-white-flowers-spring-floral-background.jpg',
     
    },
    {
      url: 'https://c4.wallpaperflare.com/wallpaper/527/294/575/flowers-daffodil-flower-sunset-yellow-flower-hd-wallpaper-preview.jpg',
     
    },
  ];
  
  
      return (
        <div className="slide-container">
          <Slide autoplayInterval={1000}>
           {slideImages.map((slideImage, index)=> (
              <div key={index}>
                <div style={{ ...divStyle, 'backgroundImage': `url(${slideImage.url})` }}>
                
                </div>
              </div>
            ))} 
          </Slide>
        </div>
      )
  
  }
  export function SpringSlider3 (){
    const spanStyle = {
      padding: '20px',
      background: '#efefef',
      color: '#000000'
    }
    
    const divStyle = {
      display: 'flex',
      alignItems: 'center',
      justifyContent: 'center',
      backgroundSize: 'cover',
      height: '500px'
    }
    const slideImages = [
     
      {
        url: 'https://images3.alphacoders.com/101/1014712.jpg',
        
      },
      {
        url: 'https://c4.wallpaperflare.com/wallpaper/712/112/876/field-dawn-morning-tulips-wallpaper-preview.jpg',
        
      },
      {
        url: 'https://www.wallpapers13.com/wp-content/uploads/2016/07/Spring-flowers-field-with-red-tulips-Desktop-Wallpaper-full-screen-1920x1200-1600x1200.jpg',
       
      },
      {
        url: 'https://wallpaperaccess.com/full/550396.jpg',
       
      },
    ];
    
    
        return (
          <div className="slide-container">
            <Slide autoplayInterval={1000}>
             {slideImages.map((slideImage, index)=> (
                <div key={index}>
                  <div style={{ ...divStyle, 'backgroundImage': `url(${slideImage.url})` }}>
                  
                  </div>
                </div>
              ))} 
            </Slide>
          </div>
        )
    
    }
    export function SpringSlider4 (){
      const spanStyle = {
        padding: '20px',
        background: '#efefef',
        color: '#000000'
      }
      
      const divStyle = {
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        backgroundSize: 'cover',
        height: '500px'
      }
      const slideImages = [
        {
          url: 'https://wallpapercave.com/wp/wp7643010.jpg',
          
        },
        
        {
          url: 'https://c4.wallpaperflare.com/wallpaper/633/590/565/bluebells-flowers-perivale-wood-wallpaper-preview.jpg',
          
        },
        {
          url: 'https://c4.wallpaperflare.com/wallpaper/765/238/330/bells-blue-bluebells-flora-wallpaper-preview.jpg',
         
        },
        {
          url: 'https://images2.alphacoders.com/106/1069768.jpg',
         
        },
      ];
      
      
          return (
            <div className="slide-container">
              <Slide autoplayInterval={1000}>
               {slideImages.map((slideImage, index)=> (
                  <div key={index}>
                    <div style={{ ...divStyle, 'backgroundImage': `url(${slideImage.url})` }}>
                    
                    </div>
                  </div>
                ))} 
              </Slide>
            </div>
          )
      
      }

      export function SummerSlider1 (){
        const spanStyle = {
          padding: '20px',
          background: '#efefef',
          color: '#000000'
        }
        
        const divStyle = {
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          backgroundSize: 'cover',
          height: '500px'
        }
        const slideImages = [
          {
            url: 'https://rare-gallery.com/mocahbig/74519-Orange-FlowerMarigold-4k-Ultra-HD-Wallpaper.jpg',
            
          },
          {
            url: 'https://images.pexels.com/photos/3524055/pexels-photo-3524055.jpeg?cs=srgb&dl=pexels-gm-rajib-3524055.jpg&fm=jpg',
           
          },
          {
            url: 'https://wallpapercave.com/wp/wp3139358.jpg',
           
          },
          {
            url: 'https://pixahive.com/wp-content/uploads/2020/09/Orange-Marigold-flower-66743-pixahive-1024x723.jpg',
           
          },
          
        ];
        
        
            return (
              <div className="slide-container">
                <Slide autoplayInterval={1000}>
                 {slideImages.map((slideImage, index)=> (
                    <div key={index}>
                      <div style={{ ...divStyle, 'backgroundImage': `url(${slideImage.url})` }}>
                      
                      </div>
                    </div>
                  ))} 
                </Slide>
              </div>
            )
        
        }
        export function SummerSlider2 (){
          const spanStyle = {
            padding: '20px',
            background: '#efefef',
            color: '#000000'
          }
          
          const divStyle = {
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            backgroundSize: 'cover',
            height: '500px'
          }
          const slideImages = [
            {
              url: 'https://c1.wallpaperflare.com/preview/932/748/964/butterfly-joe-pye-weed-flowers-yellow.jpg',
              
            },
            
            {
              url: 'https://media.istockphoto.com/id/119906000/photo/joe-pye-weed-wild-flowers.jpg?s=612x612&w=0&k=20&c=Y0jO8f0JoovrfM1w3MZ1eqJFTObOv0g73kl0XBOOC3M=',
              
            },
            {
              url: 'https://media.istockphoto.com/id/119906000/photo/joe-pye-weed-wild-flowers.jpg?s=612x612&w=0&k=20&c=Y0jO8f0JoovrfM1w3MZ1eqJFTObOv0g73kl0XBOOC3M=',
             
            },
            {
              url: 'https://www.longislandnatives.com/wp-content/uploads/2020/11/image-162.jpeg',
             
            },
          ];
          
          
              return (
                <div className="slide-container">
                  <Slide autoplayInterval={1000}>
                   {slideImages.map((slideImage, index)=> (
                      <div key={index}>
                        <div style={{ ...divStyle, 'backgroundImage': `url(${slideImage.url})` }}>
                        
                        </div>
                      </div>
                    ))} 
                  </Slide>
                </div>
              )
          
          }
          export function SummerSlider3 (){
            const spanStyle = {
              padding: '20px',
              background: '#efefef',
              color: '#000000'
            }
            
            const divStyle = {
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              backgroundSize: 'cover',
              height: '500px'
            }
            const slideImages = [
             
              {
                url: 'https://c4.wallpaperflare.com/wallpaper/267/821/109/earth-allium-flower-nature-purple-flower-hd-wallpaper-preview.jpg',
                
              },
              {
                url: 'https://wallpapers.com/images/hd/allium-purple-flower-jzd6v71l58r4z2dd.jpg',
                
              },
              {
                url: 'https://w0.peakpx.com/wallpaper/176/227/HD-wallpaper-allium-flowers-allium-stems-flowers-nature.jpg',
               
              },
              {
                url: 'https://t3.ftcdn.net/jpg/00/44/68/70/360_F_44687067_GKDwv6gbDjwwB9zTBm4ADI8CbPumiALp.jpg',
               
              },
            ];
            
            
                return (
                  <div className="slide-container">
                    <Slide autoplayInterval={1000}>
                     {slideImages.map((slideImage, index)=> (
                        <div key={index}>
                          <div style={{ ...divStyle, 'backgroundImage': `url(${slideImage.url})` }}>
                          
                          </div>
                        </div>
                      ))} 
                    </Slide>
                  </div>
                )
            
            }
            export function SummerSlider4 (){
              const spanStyle = {
                padding: '20px',
                background: '#efefef',
                color: '#000000'
              }
              
              const divStyle = {
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                backgroundSize: 'cover',
                height: '500px'
              }
              const slideImages = [
                {
                  url: 'https://c4.wallpaperflare.com/wallpaper/93/791/622/gaillardia-aristata-flowers-north-america-0087-wallpaper-preview.jpg',
                  
                },
                
                {
                  url: 'https://c4.wallpaperflare.com/wallpaper/805/973/218/gaillardia-grade-goblin-wallpaper-preview.jpg',
                  
                },
                {
                  url: 'https://s3.amazonaws.com/eit-planttoolbox-prod/media/images/Gaillardia_x_grandif_YQCTydDTnKvz.jpg',
                 
                },
                {
                  url: 'https://silverfallsseed.com/wp-content/uploads/2015/12/IMG_8247.jpg',
                 
                },
              ];
              
              
                  return (
                    <div className="slide-container">
                      <Slide autoplayInterval={1000}>
                       {slideImages.map((slideImage, index)=> (
                          <div key={index}>
                            <div style={{ ...divStyle, 'backgroundImage': `url(${slideImage.url})` }}>
                            
                            </div>
                          </div>
                        ))} 
                      </Slide>
                    </div>
                  )
              
              }

              export function WinterSlider1 (){
                const spanStyle = {
                  padding: '20px',
                  background: '#efefef',
                  color: '#000000'
                }
                
                const divStyle = {
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  backgroundSize: 'cover',
                  height: '500px'
                }
                const slideImages = [
                  {
                    url:  'https://i.pinimg.com/originals/c7/e6/fc/c7e6fc39df1ae7c384248e7389e85911.jpg',
                    
                  },
                  {
                    url: 'https://www.ruralsprout.com/wp-content/uploads/2021/12/pink-poinsettia-1024x683.jpg.webp',
                   
                  },
                  {
                    url: 'https://c4.wallpaperflare.com/wallpaper/908/642/451/poinsettia-flowers-herbs-leaves-wallpaper-preview.jpg',
                   
                  },
                  {
                    url: 'https://c4.wallpaperflare.com/wallpaper/461/41/298/5bd2475c812e7-wallpaper-preview.jpg',
                   
                  },
                  
                ];
                
                
                    return (
                      <div className="slide-container">
                        <Slide autoplayInterval={1000}>
                         {slideImages.map((slideImage, index)=> (
                            <div key={index}>
                              <div style={{ ...divStyle, 'backgroundImage': `url(${slideImage.url})` }}>
                              
                              </div>
                            </div>
                          ))} 
                        </Slide>
                      </div>
                    )
                
                }
                export function WinterSlider2 (){
                  const spanStyle = {
                    padding: '20px',
                    background: '#efefef',
                    color: '#000000'
                  }
                  
                  const divStyle = {
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    backgroundSize: 'cover',
                    height: '500px'
                  }
                  const slideImages = [
                    {
                      url: 'https://w0.peakpx.com/wallpaper/446/467/HD-wallpaper-asters-and-bees-flowers-nature-purple-asters-bees.jpg',
                      
                    },
                    
                    {
                      url: 'https://images.unsplash.com/photo-1520530035876-de527291a7e6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8YXN0ZXIlMjBmbG93ZXJ8ZW58MHx8MHx8fDA%3D&w=1000&q=80',
                      
                    },
                    {
                      url: 'https://images3.alphacoders.com/869/869234.jpg',
                     
                    },
                    {
                      url: 'https://images4.alphacoders.com/774/774431.jpg',
                     
                    },
                  ];
                  
                  
                      return (
                        <div className="slide-container">
                          <Slide autoplayInterval={1000}>
                           {slideImages.map((slideImage, index)=> (
                              <div key={index}>
                                <div style={{ ...divStyle, 'backgroundImage': `url(${slideImage.url})` }}>
                                
                                </div>
                              </div>
                            ))} 
                          </Slide>
                        </div>
                      )
                  
                  }
                  export function WinterSlider3 (){
                    const spanStyle = {
                      padding: '20px',
                      background: '#efefef',
                      color: '#000000'
                    }
                    
                    const divStyle = {
                      display: 'flex',
                      alignItems: 'center',
                      justifyContent: 'center',
                      backgroundSize: 'cover',
                      height: '500px'
                    }
                    const slideImages = [
                     
                      {
                        url: 'https://www.petalrepublic.com/wp-content/uploads/2022/04/Ultimate-Guide-to-Pansy-Flower-Meaning-Symbolism.jpeg',
                        
                      },
                      {
                        url: 'https://gardenerspath.com/wp-content/uploads/2022/03/Best-Pansy-Varieties-Feature.jpg',
                        
                      },
                      {
                        url: 'https://cdn.britannica.com/72/117272-050-B76F5F9E/Garden-pansy.jpg',
                       
                      },
                      {
                        url: 'https://static.wixstatic.com/media/af6fe6_4aaff3561c93468ea001c37d24b96a94~mv2.jpeg/v1/fill/w_900,h_600,al_c,q_85/af6fe6_4aaff3561c93468ea001c37d24b96a94~mv2.jpeg',
                       
                      },
                    ];
                    
                    
                        return (
                          <div className="slide-container">
                            <Slide autoplayInterval={1000}>
                             {slideImages.map((slideImage, index)=> (
                                <div key={index}>
                                  <div style={{ ...divStyle, 'backgroundImage': `url(${slideImage.url})` }}>
                                  
                                  </div>
                                </div>
                              ))} 
                            </Slide>
                          </div>
                        )
                    
                    }
                    export function WinterSlider4 (){
                      const spanStyle = {
                        padding: '20px',
                        background: '#efefef',
                        color: '#000000'
                      }
                      
                      const divStyle = {
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        backgroundSize: 'cover',
                        height: '500px'
                      }
                      const slideImages = [
                        {
                          url: 'https://www.gardendesign.com/pictures/images/675x529Max/site_3/alcea-rosea-halo-series-blush-pink-and-white-flower-walters-gardens_12846.jpg',
                          
                        },
                        
                        {
                          url: 'https://images.immediate.co.uk/production/volatile/sites/18/2022/05/AG6RRY-15d7eb9.jpg',
                          
                        },
                        {
                          url: 'https://myflowermeaning.com/wp-content/uploads/2019/02/Hollyhock-Flower-Meaning-and-Symbolism.jpg',
                         
                        },
                        {
                          url: 'https://plantura.garden/uk/wp-content/uploads/sites/2/2022/04/hollyhock.jpg',
                         
                        },
                      ];
                      
                      
                          return (
                            <div className="slide-container">
                              <Slide autoplayInterval={1000}>
                               {slideImages.map((slideImage, index)=> (
                                  <div key={index}>
                                    <div style={{ ...divStyle, 'backgroundImage': `url(${slideImage.url})` }}>
                                    
                                    </div>
                                  </div>
                                ))} 
                              </Slide>
                            </div>
                          )
                      
                      }

                      export function AutumnSlider1 (){
                        const spanStyle = {
                          padding: '20px',
                          background: '#efefef',
                          color: '#000000'
                        }
                        
                        const divStyle = {
                          display: 'flex',
                          alignItems: 'center',
                          justifyContent: 'center',
                          backgroundSize: 'cover',
                          height: '500px'
                        }
                        const slideImages = [
                          {
                            url:  'https://gardenerspath.com/wp-content/uploads/2022/07/Manage-Dahlia-Pests-FB.jpg',
                            
                          },
                          {
                            url: 'https://media.istockphoto.com/id/1174637483/photo/orange-dahlia-flowers.jpg?s=612x612&w=0&k=20&c=XnFuAFZQq6rb4AnW4Q7AE3nXnfEkwSOIUaywoQrQQNo=',
                           
                          },
                          {
                            url: 'https://gardening.org/wp-content/uploads/2021/08/beautiful-pink-dahlias.jpg',
                           
                          },
                          {
                            url: 'https://images.squarespace-cdn.com/content/v1/5c05a44325bf02943f5c12da/1633901906468-79UGA0XO14QUS8Y4MCT4/dahlia+cover+2.jpg?format=1500w',
                           
                          },
                          
                        ];
                        
                        
                            return (
                              <div className="slide-container">
                                <Slide autoplayInterval={1000}>
                                 {slideImages.map((slideImage, index)=> (
                                    <div key={index}>
                                      <div style={{ ...divStyle, 'backgroundImage': `url(${slideImage.url})` }}>
                                      
                                      </div>
                                    </div>
                                  ))} 
                                </Slide>
                              </div>
                            )
                        
                        }
                        export function AutumnSlider2 (){
                          const spanStyle = {
                            padding: '20px',
                            background: '#efefef',
                            color: '#000000'
                          }
                          
                          const divStyle = {
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'center',
                            backgroundSize: 'cover',
                            height: '500px'
                          }
                          const slideImages = [
                            {
                              url: 'https://cdn11.bigcommerce.com/s-1b9100svju/product_images/uploaded_images/img-1005.jpg',
                              
                            },
                            
                            {
                              url: 'https://www.almanac.com/sites/default/files/styles/or/public/image_nodes/pink%20lilies-Anastasios71-SS.jpeg?itok=mmh-o8yf',
                              
                            },
                            {
                              url: 'https://cdn.britannica.com/77/120977-050-41EE9568/Easter-lily.jpg',
                             
                            },
                            {
                              url: 'https://www.gardendesign.com/pictures/images/675x529Max/site_3/asiatic-lily-cappuccino-lily-creative-commons_11653.jpg',
                             
                            },
                          ];
                          
                          
                              return (
                                <div className="slide-container">
                                  <Slide autoplayInterval={1000}>
                                   {slideImages.map((slideImage, index)=> (
                                      <div key={index}>
                                        <div style={{ ...divStyle, 'backgroundImage': `url(${slideImage.url})` }}>
                                        
                                        </div>
                                      </div>
                                    ))} 
                                  </Slide>
                                </div>
                              )
                          
                          }
                          export function AutumnSlider3 (){
                            const spanStyle = {
                              padding: '20px',
                              background: '#efefef',
                              color: '#000000'
                            }
                            
                            const divStyle = {
                              display: 'flex',
                              alignItems: 'center',
                              justifyContent: 'center',
                              backgroundSize: 'cover',
                              height: '500px'
                            }
                            const slideImages = [
                             
                              {
                                url: 'https://cdn.woolmans.com/product-images/op/z/PEN-077z.jpg',
                                
                              },
                              {
                                url: 'https://www.bluestoneperennials.com/img/PEQR/650/PEQR-0-Penstemon-Quartz-Rose-DSC3849.1605200419.jpg',
                                
                              },
                              {
                                url: 'https://www.atozflowers.com/wp-content/uploads/2018/10/450px-Penstemon_murrayanus_2.jpg',
                               
                              },
                              {
                                url: 'https://media.istockphoto.com/id/1266251112/photo/penstemon.jpg?s=612x612&w=0&k=20&c=C0i_r_3YKQ6c0IprY2jBKslyVGg2RgOHQxM80SvAYQE=',
                               
                              },
                            ];
                            
                            
                                return (
                                  <div className="slide-container">
                                    <Slide autoplayInterval={1000}>
                                     {slideImages.map((slideImage, index)=> (
                                        <div key={index}>
                                          <div style={{ ...divStyle, 'backgroundImage': `url(${slideImage.url})` }}>
                                          
                                          </div>
                                        </div>
                                      ))} 
                                    </Slide>
                                  </div>
                                )
                            
                            }
                            export function AutumnSlider4 (){
                              const spanStyle = {
                                padding: '20px',
                                background: '#efefef',
                                color: '#000000'
                              }
                              
                              const divStyle = {
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'center',
                                backgroundSize: 'cover',
                                height: '500px'
                              }
                              const slideImages = [
                                {
                                  url: 'https://images5.alphacoders.com/432/thumb-1920-432353.jpg',
                                  
                                },
                                
                                {
                                  url: 'https://wallpapercave.com/wp/wp10067678.jpg',
                                  
                                },
                                {
                                  url: 'https://c4.wallpaperflare.com/wallpaper/91/97/280/beautiful-morning-flowers-blue-cornflowers-wallpaper-preview.jpg',
                                 
                                },
                                {
                                  url: 'https://w0.peakpx.com/wallpaper/575/753/HD-wallpaper-cornflower-blue-flower-blue-centaurea.jpg',
                                 
                                },
                              ];
                              
                              
                                  return (
                                    <div className="slide-container">
                                      <Slide autoplayInterval={1000}>
                                       {slideImages.map((slideImage, index)=> (
                                          <div key={index}>
                                            <div style={{ ...divStyle, 'backgroundImage': `url(${slideImage.url})` }}>
                                            
                                            </div>
                                          </div>
                                        ))} 
                                      </Slide>
                                    </div>
                                  )
                              
                              }