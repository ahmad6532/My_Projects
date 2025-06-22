import React from "react";
import { FormElement } from "./FormElements";
import { useDraggable } from "@dnd-kit/core";

const SideBarBtnElement = ({ formElement }: { formElement: FormElement }) => {
    const draggable = useDraggable({
        id: `designer-btn-${formElement.type}`,
        data: {
            type: formElement.type,
            isDesignerBtnElement: true,
        },
    });
    const { icon, label } = formElement.designerBtnElement;
    return (
        <button className={`form-sidebar-btn ${draggable.isDragging && 'border-1 border-brand opacity-50'}`} ref={draggable.setNodeRef} {...draggable.listeners} {...draggable.attributes}>
            {icon}
            {label}
        </button>
    );
};



export function SideBarBtnElementOverlay ({ formElement }: { formElement: FormElement }) {
    const { icon, label } = formElement.designerBtnElement;
    return (
        <button className={`form-sidebar-btn opacity-50`} >
            {icon}
            {label}
        </button>
    );
};

export default SideBarBtnElement;
