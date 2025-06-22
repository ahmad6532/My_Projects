import {
    DraggableAttributes,
    DraggableSyntheticListeners,
} from "@dnd-kit/core";
import {
    ElementsType,
    FormElement,
    FormElementInstance,
    SubmitFunction,
} from "../FormElements";
import useDesigner from "../hooks/useDesigner";
import z from "zod";
import {useForm,Controller} from 'react-hook-form'
import {zodResolver}  from '@hookform/resolvers/zod'
import { useEffect, useRef, useState } from "react";
import {Alert, Button, Checkbox, Divider, FormControlLabel, IconButton, Paper, Stack, Table, TableBody, TableCell, TableContainer, TableHead, TableRow, TextField, Tooltip, Typography} from '@mui/material';
import Select, { SingleValue } from "react-select";
import AsyncSelect from 'react-select/async';
import AddIcon from '@mui/icons-material/Add';
import CloseIcon from '@mui/icons-material/Close';
import axios from "axios";
import { toast } from "react-toastify";
import DeleteIcon from "@mui/icons-material/Delete";

const type: ElementsType = "DMDField";

const extraAttributes = {
    label: "DMD ",
    helperText: "Helper text",
    required: false,
};

const propertiseSchema = z.object({
    label: z.string().min(3).max(300),
    helperText: z.string().max(500),
    required: z.boolean().default(false),
    id:z.string().min(1).max(200),
})


export const DMDBlock: FormElement = {
    type,

    construct: (id: string) => ({
        id,
        type,
        extraAttributes,
    }),
    designerBtnElement: {
        icon: (
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M3 7.8C3 6.11984 3 5.27976 3.32698 4.63803C3.6146 4.07354 4.07354 3.6146 4.63803 3.32698C5.27976 3 6.11984 3 7.8 3H16.2C17.8802 3 18.7202 3 19.362 3.32698C19.9265 3.6146 20.3854 4.07354 20.673 4.63803C21 5.27976 21 6.11984 21 7.8V16.2C21 17.8802 21 18.7202 20.673 19.362C20.3854 19.9265 19.9265 20.3854 19.362 20.673C18.7202 21 17.8802 21 16.2 21H7.8C6.11984 21 5.27976 21 4.63803 20.673C4.07354 20.3854 3.6146 19.9265 3.32698 19.362C3 18.7202 3 17.8802 3 16.2V7.8Z" stroke="black" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
            <path d="M13.8333 7.3C13.8333 7.01997 13.8333 6.87996 13.7788 6.773C13.7309 6.67892 13.6544 6.60243 13.5603 6.5545C13.4534 6.5 13.3134 6.5 13.0333 6.5H10.9667C10.6866 6.5 10.5466 6.5 10.4397 6.5545C10.3456 6.60243 10.2691 6.67892 10.2212 6.773C10.1667 6.87996 10.1667 7.01997 10.1667 7.3V9.36667C10.1667 9.64669 10.1667 9.78671 10.1122 9.89366C10.0642 9.98774 9.98774 10.0642 9.89366 10.1122C9.78671 10.1667 9.64669 10.1667 9.36667 10.1667H7.3C7.01997 10.1667 6.87996 10.1667 6.773 10.2212C6.67892 10.2691 6.60243 10.3456 6.5545 10.4397C6.5 10.5466 6.5 10.6866 6.5 10.9667V13.0333C6.5 13.3134 6.5 13.4534 6.5545 13.5603C6.60243 13.6544 6.67892 13.7309 6.773 13.7788C6.87996 13.8333 7.01997 13.8333 7.3 13.8333H9.36667C9.64669 13.8333 9.78671 13.8333 9.89366 13.8878C9.98774 13.9358 10.0642 14.0123 10.1122 14.1063C10.1667 14.2133 10.1667 14.3533 10.1667 14.6333V16.7C10.1667 16.98 10.1667 17.12 10.2212 17.227C10.2691 17.3211 10.3456 17.3976 10.4397 17.4455C10.5466 17.5 10.6866 17.5 10.9667 17.5H13.0333C13.3134 17.5 13.4534 17.5 13.5603 17.4455C13.6544 17.3976 13.7309 17.3211 13.7788 17.227C13.8333 17.12 13.8333 16.98 13.8333 16.7V14.6333C13.8333 14.3533 13.8333 14.2133 13.8878 14.1063C13.9358 14.0123 14.0123 13.9358 14.1063 13.8878C14.2133 13.8333 14.3533 13.8333 14.6333 13.8333H16.7C16.98 13.8333 17.12 13.8333 17.227 13.7788C17.3211 13.7309 17.3976 13.6544 17.4455 13.5603C17.5 13.4534 17.5 13.3134 17.5 13.0333V10.9667C17.5 10.6866 17.5 10.5466 17.4455 10.4397C17.3976 10.3456 17.3211 10.2691 17.227 10.2212C17.12 10.1667 16.98 10.1667 16.7 10.1667H14.6333C14.3533 10.1667 14.2133 10.1667 14.1063 10.1122C14.0123 10.0642 13.9358 9.98774 13.8878 9.89366C13.8333 9.78671 13.8333 9.64669 13.8333 9.36667V7.3Z" stroke="black" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
            </svg>

        ),
        label: "DM+D",
    },
    designerComponent: DesignerComponent,
    formComponent: FormComponent,
    propertiseComponent: PropertiseComponent,
    validate: (formElement: FormElementInstance, currentValue: string):boolean => {
        const element = formElement as CustomInstance;
        if(element.extraAttributes.required){
            return currentValue.length > 0;
        }
        return true;
    },
};

type CustomInstance = FormElementInstance & {
    extraAttributes: typeof extraAttributes;
};

function DesignerComponent({
    pageId,
    elementInstance,
    listeners,
    attributes,
}: {
    pageId: string;
    elementInstance: FormElementInstance;
    listeners?: DraggableSyntheticListeners;
    attributes?: DraggableAttributes;
}) {
    const { removeElement, setSelectedItem,updateElement,setSelectedPageId } = useDesigner();
    const element = elementInstance as CustomInstance;
    const { label, placeholder, helperText, required } = element.extraAttributes;

    function updateRequired() {
        updateElement(pageId,element.id, {
            ...element,
            extraAttributes: {
                ...element.extraAttributes,
                required: !element.extraAttributes.required,
            },
        });
    }
    return (
        <div
            className="main-question-wrapper"
            key={element.id}
            onClick={(e) => {
                e.stopPropagation();
                setSelectedItem(element);
                setSelectedPageId(pageId);
                
            }}
        >
            <div className="drag-area" {...listeners} {...attributes}>
                <svg viewBox="0 0 20 20">
                    <path d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-8a2 2 0 1 0-.001-4.001A2 2 0 0 0 13 6zm0 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z"></path>
                </svg>
            </div>
            <div className="input-area">
                <div style={{paddingBottom:'16px'}}>
                    <Typography variant="h4" fontWeight='600' className="m-0 p-0">
                        {label} {required && "*"}
                    </Typography>
                    {helperText && <p className="m-0 desc mt-1">{helperText}</p>}
                </div>
                <Typography my={1} color='text.secondary' variant="body1">Start typing and select the relevant medicine from the list.</Typography>
                <input
                    type="text"
                    readOnly
                    disabled
                />
                <Typography my={1} color='text.secondary' variant="body1">Then select the relevant product from the list.</Typography>
                <input
                    className="my-2"
                    type="text"
                    readOnly
                    disabled
                />
                <Typography variant="body1" my={1} color={'text.secondary'}>Or specify other</Typography>
                <input
                    type="text"
                    readOnly
                    disabled
                />

                <Button className="mt-4" variant="contained" sx={{color:'white'}} disabled>Add another</Button>
            </div>

            <div className="question-actions">
                <div></div>
                <div className="right-actions">
                    <button className="light-btn" onClick={(e) => {e.stopPropagation();setSelectedItem(element)}}>
                        <svg
                            width="18"
                            height="18"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M3 8L15 8M15 8C15 9.65686 16.3431 11 18 11C19.6569 11 21 9.65685 21 8C21 6.34315 19.6569 5 18 5C16.3431 5 15 6.34315 15 8ZM9 16L21 16M9 16C9 17.6569 7.65685 19 6 19C4.34315 19 3 17.6569 3 16C3 14.3431 4.34315 13 6 13C7.65685 13 9 14.3431 9 16Z"
                                stroke="black"
                                strokeWidth="2"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                            />
                        </svg>
                        Settings
                    </button>
                    <button className="light-btn" onClick={(e) => {updateRequired()}} style={{background:required?'rgba(0,0,0,0.03)':'transparent'}}>
                        <svg
                            className="required-icon"
                            version="1.1"
                            xmlns="http://www.w3.org/2000/svg"
                            width="13"
                            height="13"
                            viewBox="0 0 256 256"
                        >
                            <path
                                fill="#000000"
                                d="M223,160.1L167.3,128L223,95.9c9.4-5.4,12.6-17.5,7.2-26.9c-5.4-9.4-17.5-12.6-26.9-7.2l-55.6,32.1V29.7c0-10.9-8.8-19.7-19.7-19.7c-10.9,0-19.7,8.8-19.7,19.7v64.3L52.7,61.8c-9.4-5.4-21.4-2.2-26.9,7.2c-5.4,9.4-2.2,21.4,7.2,26.9L88.7,128L33,160.1c-9.4,5.4-12.6,17.5-7.2,26.9c5.4,9.4,17.5,12.6,26.9,7.2l55.6-32.1v64.3c0,10.9,8.8,19.7,19.7,19.7c10.9,0,19.7-8.8,19.7-19.7V162l55.6,32.1c9.4,5.4,21.4,2.2,26.9-7.2C235.6,177.6,232.4,165.6,223,160.1z"
                            />
                        </svg>
                        Required
                    </button>
                    <button
                        className="light-btn"
                        onClick={(e) => {
                            e.stopPropagation;
                            removeElement(pageId,element.id);
                        }}
                    >
                        <svg
                            width="18"
                            height="18"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M9 3H15M3 6H21M19 6L18.2987 16.5193C18.1935 18.0975 18.1409 18.8867 17.8 19.485C17.4999 20.0118 17.0472 20.4353 16.5017 20.6997C15.882 21 15.0911 21 13.5093 21H10.4907C8.90891 21 8.11803 21 7.49834 20.6997C6.95276 20.4353 6.50009 20.0118 6.19998 19.485C5.85911 18.8867 5.8065 18.0975 5.70129 16.5193L5 6M10 10.5V15.5M14 10.5V15.5"
                                stroke="black"
                                strokeWidth="2"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                            />
                        </svg>
                        Delete
                    </button>
                </div>
            </div>
        </div>
    );
}
function FormComponent ({
    elementInstance,
    submitValue,
    isInvalid,
    defaultValue
}: {
    elementInstance: FormElementInstance;
    submitValue?: SubmitFunction;
    isInvalid?: boolean;
    defaultValue?: any;
}) {
    const { removeElement, setSelectedItem, selectedItem } = useDesigner();
    const [vtmValue, setVtmValue] = useState<SingleValue<{ value: string; label: string }> | null>(null);
    const [vmpValue, setVmpValue] = useState<SingleValue<{ value: string; label: string }> | null>(null);
    const [vmpArray, setVmpArray] = useState<{ value: string; label: string }[]>([]);
    const [customEntries, setCustomEntries] = useState<{ value: string | null; label: string }[]>([]);
    const [customInput, setCustomInput] = useState<string>('');
    const [error, setError] = useState(false);
    const [arrayError, setArrayError] = useState(false);
    const serverAddress = `${window.location.protocol}//${window.location.hostname}:${window.location.port}`;
    const url = `${serverAddress}/api/lfpse/dmd_vtm`;
    const url2 = `${serverAddress}/api/lfpse/dmd_vmp`;

    const fetchData = async (url: string, queryParams: any) => {
        try {
            const response = await axios.get(url, {
                params: queryParams
            });
    
            return response.data;
        } catch (error) {
            console.error('Error fetching data:', error);
            throw error;
        }
    };

    useEffect(() => {
        setError(isInvalid === true);
    }, [isInvalid]);

    const element = elementInstance as CustomInstance;
    const { label, placeholder, helperText, required, isSearchable, options, webUrl, path, commonValue, commonLabel } = element.extraAttributes;

    const loadOptions = async (inputValue: string, callback: (options: { value: any; label: any; }[]) => void): Promise<any> => {
        if (!inputValue) {
            callback([]);
            return;
        }
        const queryParams = {
            q: inputValue,
        };

        try {
            const data = await fetchData(url, queryParams);
            if (!Array.isArray(data.results)) {
                setArrayError(true);
                return;
            }
            if (data.results) {
                const options = data.results.map((option: any) => ({ value: option.VTMID, label: option.text }));
                callback(options);
                setArrayError(false);
            } else {
                callback([]);
            }
        } catch (error) {
            console.error('Error loading options:', error);
            toast.error('Error loading options:');
            callback([]);
        }
    };

    useEffect(() => {
        const fetchDataAsync = async () => {
            if (!vtmValue) {setVmpValue(null); setVmpArray([]); return};
            const queryParams = {
                q: vtmValue?.value,
            };
            try {
                const data = await fetchData(url2, queryParams);
                setVmpArray(data.results.map((option: any) => ({ value: option.VMPID, label: option.text })));
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        };

        fetchDataAsync();
    }, [vtmValue]);

    const handleAddEntry = () => {
        if (vtmValue && vmpValue) {
            setCustomEntries([...customEntries, { value: vmpValue.value, label: `${vmpValue.label}` } , { value: vtmValue.value, label: `${vtmValue.label}` }]);
            setVtmValue(null);
            setVmpValue(null);
        } else if (vtmValue) {
            setCustomEntries([...customEntries, { value: vtmValue.value, label: vtmValue.label }]);
            setVtmValue(null);
        } else if (customInput.trim()) {
            setCustomEntries([...customEntries, { value: null, label: customInput }]);
            setCustomInput('');
        }
    };

    const handleRemoveEntry = (index: number) => {
        setCustomEntries(customEntries.filter((_, i) => i !== index));
    };

    return (
        <div className="main-question-wrapper prev">
            <div className="input-area">
                <div style={{ paddingBottom: '16px' }}>
                    <Typography variant="h4" fontWeight={600} className="m-0 p-0">
                        {label} {required && "*"}
                    </Typography>
                    {helperText && <p className="m-0 desc mt-1">{helperText}</p>}
                </div>

            <Paper sx={{mb:3}}>
                <Stack ml={3}>
                        {customEntries.map((entry,index) => (
                            <Stack direction={"row"} alignItems="center" key={index} justifyContent='space-between'>
                                <Typography variant="h5" fontWeight='bold' sx={{paddingBottom:'0px !important'}}>
                                    {entry.label}
                                </Typography>
                                <IconButton onClick={() => handleRemoveEntry(index)}><DeleteIcon/></IconButton>
                            </Stack>
                                
                        ))}
                </Stack>
            </Paper>
                <Stack>
                    <Typography my={1} color='text.secondary' variant="body1">Start typing and select the relevant medicine from the list.</Typography>
                    <AsyncSelect 
                    value={vtmValue}
                        loadOptions={loadOptions} 
                        isClearable 
                        isSearchable={isSearchable} 
                        onChange={(value) => {
                            setVtmValue(value);
                            if (!submitValue) return;
                            if (!value) return;
                            const valid = DMDBlock.validate(element, value?.label);
                            setError(!valid);
                            submitValue(element.id, value?.label);
                        }}
                    />
                    <Typography my={1} color='text.secondary' mt={4} variant="body1">Then select the relevant product from the list.</Typography>
                    <Select 
                        value={vmpValue}
                        options={vmpArray} 
                        isClearable 
                        isSearchable={false} 
                        onChange={(value) => {
                            setVmpValue(value);
                            if (!submitValue) return;
                            if (!value) return;
                            const valid = DMDBlock.validate(element, value?.label);
                            setError(!valid);
                            submitValue(element.id, value?.label);
                        }}
                    />
                    {(!vtmValue && !vmpValue) && (
                        <>
                            <Typography my={1} color='text.secondary' mt={2} variant="body1">Or specify other</Typography>
                            <input 
                                value={customInput} 
                                onChange={(e) => setCustomInput(e.target.value)} 
                            />
                        </>
                    )}
                    <Button 
                        variant="contained" 
                        sx={{ color: 'white', alignSelf: 'flex-start', mt: 2 }} 
                        onClick={handleAddEntry}
                    >
                        Add another
                    </Button>
                    {arrayError && <Alert sx={{ width: 'fit-content', position: 'absolute', bottom: "5px" }} severity="error">Make sure all fields are valid for web service url</Alert>}
                </Stack>
            </div>
            {error && 
                <Alert sx={{ width: '94%', position: 'absolute', bottom: "5px" }} severity="error">Response is required</Alert>
            }
        </div>
    );
}

type propertiseFormSchemaType = z.infer<typeof propertiseSchema>
function PropertiseComponent({pageId,elementInstance}:{
    pageId:string
    ,elementInstance: FormElementInstance}){
        const element = elementInstance as CustomInstance;
        const {control, handleSubmit, formState: { errors }, getValues,reset,setError,setValue,watch} = useForm<propertiseFormSchemaType>({
            resolver: zodResolver(propertiseSchema),
            mode:'onChange',
            defaultValues:{
                label: element.extraAttributes.label,
                helperText: element.extraAttributes.helperText,
                required: element.extraAttributes.required,
                id:element.id,
            }
        });


        const {updateElement,elements,setSelectedItem,selectedPageId} = useDesigner();
        const pageIndex = elements.findIndex(page => page.id == selectedPageId)
        const found = elements[pageIndex].questions.find(e => e.id === element.id)
        useEffect(() => {
            if (found) {
                reset({
                    id: found.id,
                    label: found.extraAttributes?.label,
                    helperText: found.extraAttributes?.helperText,
                    required: found.extraAttributes?.required,
                });
            }
        }, [element, reset]);

        function applyChanges(values:propertiseFormSchemaType){
            const {label,helperText,required} = values;
            updateElement(pageId,element.id,{
                ...element,extraAttributes:{
                    label,
                    helperText,
                    required,
                }
            })
        }
        const isIdUnique = (id: string) => {
            const allQuestions = elements.flatMap(page => page.questions);
            return !allQuestions.some(el => el.id === id);
        };
        function idchange(value:string){
            if(isIdUnique(value)){
                updateElement(pageId,element.id,{
                    ...element,id:value
                })
                setSelectedItem(element);
                return;
            }else{
                setError('id', {
                    type: 'manual',
                    message: 'ID must be unique'
                });
            }
        }
        return (
            <>
            <Typography variant="body1" color="GrayText">General</Typography>
                <Divider orientation="horizontal" sx={{ marginBottom: 3, borderColor: 'rgba(0,0,0,0.5)' }} />
                <Stack direction={'row'} spacing={1} alignItems={"center"} mb={2}>
                <Controller
                        name="id"
                        shouldUnregister
                        control={control}
                        render={({ field }) => (
                            <TextField
                                {...field}
                                onBlur={e=>idchange(e.target.value)}
                                error={!!errors.id}
                                helperText={errors.id ? errors.id.message : ''}
                                label="Question ID"
                                fullWidth
                                variant="outlined"
                                size="small"
                                multiline
                            />
                        )}
                    />
                    <Tooltip title="A Question ID that is not visible to users">
                    <IconButton>
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.09 9C9.3251 8.33167 9.78915 7.76811 10.4 7.40913C11.0108 7.05016 11.7289 6.91894 12.4272 7.03871C13.1255 7.15849 13.7588 7.52152 14.2151 8.06353C14.6713 8.60553 14.9211 9.29152 14.92 10C14.92 12 11.92 13 11.92 13M12 17H12.01M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z" stroke="#72C4BA" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"/>
                    </svg>

                    </IconButton>
                    </Tooltip>
                </Stack>
            <form onChange={handleSubmit(applyChanges)} className="mt-4 mx-auto w-100">
                
                <Controller
                    name="label"
                    control={control}
                    render={({ field }) => (
                        <TextField
                            {...field}
                            error={!!errors.label}
                            helperText={errors.label ? "Please enter a valid value" : ''}
                            label="Label"
                            fullWidth
                            variant="outlined"
                            size="small"
                        />
                    )}
                />

                <Controller
                    name="helperText"
                    control={control}
                    render={({ field }) => (
                        <TextField
                            {...field}
                            error={!!errors.helperText}
                            multiline
                            minRows={2}
                            maxRows={4}
                            helperText={errors.helperText ? "Please enter a valid value" : ''}
                            label="Description"
                            fullWidth
                            sx={{ marginY: 2 }}
                            variant="outlined"
                            size="small"
                        />
                    )}
                />

            </form>
            <Stack direction="row" spacing={1} sx={{ marginTop: 1 }} alignItems="center">
                <Controller
                    name="required"
                    control={control}
                    render={({ field }) => (
                        <Checkbox
                            {...field}
                            checked={field.value}
                            onChange={(e) => {
                                field.onChange(e.target.checked);
                                applyChanges({ ...getValues(), required: e.target.checked });
                            }}
                        />
                    )}
                />
                <Typography>Required</Typography>
            </Stack>


        </>
        )
}


