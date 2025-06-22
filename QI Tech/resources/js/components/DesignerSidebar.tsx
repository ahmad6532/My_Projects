import React, { ChangeEvent, useState } from 'react'
import SideBarBtnElement from './SideBarBtnElement'
import { FormElement, FormElements } from './FormElements'
import { Stack } from '@mui/material'

const DesignerSidebar = () => {
  const [searchTerm, setSearchTerm] = useState('')

  const handleSearchChange = (event:ChangeEvent<HTMLInputElement>) => {
    setSearchTerm(event.target.value.toLowerCase())
  }

  const filterElements = (elements:FormElement[]) => {
    return elements.filter(element => element.type.toLowerCase().includes(searchTerm))
  }

  const layoutElements = filterElements([
    FormElements.TitleField,
    FormElements.SubTitleField,
    FormElements.ParagraphField,
    FormElements.SeparatorField,
    FormElements.SpacerField
  ])

  const formElements = filterElements([
    FormElements.TextField,
    FormElements.NumberField,
    FormElements.TextAreaField,
    FormElements.DateField,
    FormElements.SelectField,
    FormElements.CheckboxField
  ]);

  const lfpseElements = filterElements([
    FormElements.DMDField
  ]);

  return (
    <aside className='form-sidebar-wrap custom-scroll custom-scroll-form' style={{position:'fixed',overflowY:'scroll',top:'73px',minHeight:'90vh',maxHeight:'80vh'}}>
      <div className='p-0 m-0 w-100'>
        <div className="input-group rounded mt-1" style={{width:'100%'}}>
                <span className="input-group-text border-0 bg-transparent search-addon" id="search-addon">
                    <i className="fas fa-search" style={{color:"#969697"}} aria-hidden="true"></i>
                </span>
                <input 
                  type="search" 
                  className="form-control rounded shadow-none search-input" 
                  placeholder="Search" 
                  value={searchTerm}
                  onChange={handleSearchChange}
                />
          </div>
          <hr className='m-0 w-100 border-2 mt-1' style={{borderColor:'#D5D5D5',opacity:1}} />
        <p className='m-0 mt-2' style={{color:'#636363'}}>Layout Elements</p>
        <Stack direction='row' gap={2} flexWrap={'wrap'}>
          {layoutElements.map(element => (
            <SideBarBtnElement key={element.type} formElement={element} />
          ))}
        </Stack>
        <p className='m-0 mt-2' style={{color:'#636363'}}>Form Elements</p>
        <Stack direction='row' gap={2} flexWrap={'wrap'}>
          {formElements.map(element => (
            <SideBarBtnElement key={element.type} formElement={element} />
          ))}
        </Stack>
        <p className='m-0 mt-2' style={{color:'#636363'}}>LFPSE Elements</p>
        <Stack direction='row' gap={2} flexWrap={'wrap'}>
          {lfpseElements.map(element => (
            <SideBarBtnElement key={element.type} formElement={element} />
          ))}
        </Stack>
      </div>
    </aside>
  )
}

export default DesignerSidebar
