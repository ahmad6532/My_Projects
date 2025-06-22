import { Active, DragOverlay, useDndMonitor,Over } from '@dnd-kit/core'
import React, { ElementType, useState } from 'react'
import { SideBarBtnElementOverlay } from './SideBarBtnElement';
import { ElementsType, FormElements } from './FormElements';
import useDesigner from './hooks/useDesigner';

const DragOverlayWrapper = () => {
    const [draggedItem,setDraggedItem] = useState<Active | null>(null);
    const {elements} = useDesigner();
    useDndMonitor({
        onDragStart : (event) => {
            setDraggedItem(event.active)
        },
        onDragCancel : (event) => {
            setDraggedItem(null)
        },
        onDragEnd : (event) => {
            setDraggedItem(null)
        }
    })

    if (!draggedItem) return null;
    let node = <div>test overlay</div>
    const isSideBarBtnElemetn = draggedItem.data?.current?.isDesignerBtnElement;
    if(isSideBarBtnElemetn){
        const type = draggedItem.data?.current?.type as ElementsType;
        node = <SideBarBtnElementOverlay formElement={FormElements[type]}/>
    }
    const isDesignerElement = draggedItem.data?.current?.isDesignerElement;
    if(isDesignerElement){
        const elementId = draggedItem.data?.current?.elementId;
        const allQuestions = elements.flatMap(page => page.questions);
        const element = allQuestions.find(element => element.id === elementId);
        if(!element) node = <div>Element not found</div>
        else{
            const DesignerElementComponent = FormElements[element.type].designerComponent;
            switch(element.type){
                case 'TextField':
                    node = <div className='bg-white rounded-5 mx-auto p-2 px-4 shadow' style={{width:'fit-content',cursor:'grabbing'}}>{element.extraAttributes?.label}</div>
                break;
                case 'ParagraphField':
                    node = <div className='bg-white rounded-5 mx-auto p-2 px-4 shadow' style={{width:'fit-content',cursor:'grabbing'}}>{element.extraAttributes?.text}</div>
                break;
                case 'TitleField':
                    node = <div className='bg-white rounded-5 mx-auto p-2 px-4 shadow' style={{width:'fit-content',cursor:'grabbing'}}>{element.extraAttributes?.title}</div>
                break;
                case 'SubTitleField':
                    node = <div className='bg-white rounded-5 mx-auto p-2 px-4 shadow' style={{width:'fit-content',cursor:'grabbing'}}>{element.extraAttributes?.title}</div>
                break;
                default:
                    node = <div className='bg-white rounded-5 mx-auto p-2 px-4 shadow' style={{width:'fit-content',cursor:'grabbing'}}>{element.type}</div>
            }
            
        }
    }
  return (
    <DragOverlay>{node}</DragOverlay>
  )
}

export default DragOverlayWrapper