import React from "react";
import "../style.css";

const Gallery = ()=>{

let data=[
    {
        id:1,
        imgSrc:'assets/img000.jpg'
    },
    {
        id:2,
        imgSrc:'assets/bg2.jpeg'
    },
    {
        id:3,
        imgSrc:'assets/b4.jpeg'
    },
    {
        id:4,
        imgSrc:'assets/alium2.jpeg'
    },
    {
        id:5,
        imgSrc:'assets/water.jpg'
    },
    {
        id:6,
        imgSrc:'assets/cback1.jpg'
    },
    {
        id:7,
        imgSrc:'assets/autumn2.jpeg'
    },
    {
        id:8,
        imgSrc:'assets/h1.jpeg'
    },
    {
        id:9,
        imgSrc:'assets/blue.jpeg'
    },
    {
        id:10,
        imgSrc:'assets/cam2.jpeg'
    },
    {
        id:11,
        imgSrc:'assets/cyclamen3.jpeg'
    },
    {
        id:12,
        imgSrc:'assets/h2.jpeg'
    },
    {
        id:13,
        imgSrc:'assets/flow.jpg'
    },
    {
        id:14,
        imgSrc:'assets/flower.jpg'
    },
    {
        id:15,
        imgSrc:'assets/gemi2.jpeg'
    },
    {
        id:16,
        imgSrc:'assets/get_started.jpeg'
    },
    {
        id:17,
        imgSrc:'assets/get1.jpeg'
    },
    {
        id:18,
        imgSrc:'assets/h5.jpeg'
    },
    {
        id:19,
        imgSrc:'assets/h6.jpeg'
    },
    {
        id:20,
        imgSrc:'assets/h7.jpeg'
    },
    {
        id:21,
        imgSrc:'assets/h10.jpeg'
    },
    {
        id:22,
        imgSrc:'assets/h11.jpeg'
    },
    {
        id:23,
        imgSrc:'assets/lily1.jpeg'
    },
    {
        id:24,
        imgSrc:'assets/side1.jpeg'
    },
    {
        id:25,
        imgSrc:'assets/slug9.jpeg'
    },
    {
        id:26,
        imgSrc:'assets/slugg.jpeg'
    },
    {
        id:27,
        imgSrc:'assets/snow2.jpeg'
    },
    {
        id:28,
        imgSrc:'assets/snow4.jpeg'
    },
    {
        id:29,
        imgSrc:'assets/sslug.jpg'
    },
    {
        id:30,
        imgSrc:'assets/score2.jpeg'
    }

    
]
    return(
        <div>
            <div class="multigdiv">
            <img class="multig"src="assets/galleryheading2.png"/>
            </div>
            <div class="gallery">
            
            {
                data.map((item,index)=>{
                    return(
                        <div key={index} className="pics">
                            <img src={item.imgSrc} style={{width:'100%'}} />
                        </div>
                    )
                }
            )}
        </div>
        </div>
        
    );
}
export default Gallery;