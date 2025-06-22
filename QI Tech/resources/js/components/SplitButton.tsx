import * as React from "react";
import Button from "@mui/material/Button";
import ButtonGroup from "@mui/material/ButtonGroup";
import ArrowDropDownIcon from "@mui/icons-material/ArrowDropDown";
import ClickAwayListener from "@mui/material/ClickAwayListener";
import Grow from "@mui/material/Grow";
import Paper from "@mui/material/Paper";
import Popper from "@mui/material/Popper";
import MenuItem from "@mui/material/MenuItem";
import MenuList from "@mui/material/MenuList";
import { FormElementInstance,FormPage } from "./FormElements";
import useDesigner from "./hooks/useDesigner";
import { Alert, Snackbar } from "@mui/material";
import axios from "axios";
import { toast } from "react-toastify";

const options = [
    "Import",
    "Export",
    "Save Draft",
];
type AlertType = 'error' | 'warning' | 'info' | 'success';
interface AlertState {
    open: boolean;
    type: AlertType;
    message?: string
}

export default function SplitButton() {
    const [open, setOpen] = React.useState(false);
    const [alertState, setAlertState] = React.useState<AlertState>({
        open: false,
        type: 'warning',
        message: 'Form is Empty!'
    });
    const anchorRef = React.useRef<HTMLDivElement>(null);
    const [selectedIndex, setSelectedIndex] = React.useState(1);
    const {elements,setElements} = useDesigner();
    const serverAddress = `${window.location.protocol}//${window.location.hostname}:${window.location.port}`;
    const path = window.location.pathname;
    const segments = path.split('/');
    const id = segments[segments.length - 1];

    const handleClick = () => {
        console.info(`You clicked ${options[selectedIndex]}`);
        if(options[selectedIndex] == 'Export'){
            downloadJsonFile(elements,'Form.json')
        }else if(options[selectedIndex] == 'Import'){
            handleButtonClick()
        }
    };

    const handleMenuItemClick = (
        event: React.MouseEvent<HTMLLIElement, MouseEvent>,
        index: number
    ) => {
        setSelectedIndex(index);
        console.info(`You clicked ${options[index]}`);
        if(options[index] == 'Export'){
            downloadJsonFile(elements,'Form.json')
        }else if(options[index] == 'Import'){
            handleButtonClick()
        }else if(options[index] == 'Save Draft'){
            handlePostRequest(`${serverAddress}/location/bespokeforms/get_draft_form_json${id}`)
        }

        setOpen(false);
    };

    const handleToggle = () => {
        setOpen((prevOpen) => !prevOpen);
    };

    const handleClose = (event: Event) => {
        if (
            anchorRef.current &&
            anchorRef.current.contains(event.target as HTMLElement)
        ) {
            return;
        }

        setOpen(false);
    };
    const openAlert = (alertType: AlertType, message: string) => {
        setAlertState({ open: true, type: alertType,message });
    };
    const closeAlert = () => {
        setAlertState({ open: false, type: 'warning' });
    };

    const downloadJsonFile = (jsonData: FormPage[], filename:string) => {
        if(elements.length == 0){
            openAlert('warning','Form is Empty !');
            return;
        }
        // Convert JSON to Blob
        const blobData = new Blob([JSON.stringify(jsonData, null, 2)], { type: 'application/json' });
    
        // Create object URL
        const url = URL.createObjectURL(blobData);
    
        // Create a link element, click it, and remove it
        const link = document.createElement('a');
        link.href = url;
        link.download = filename;
        link.click();
    
        // Cleanup
        URL.revokeObjectURL(url);
        openAlert('success','Form Exported Successfully!');
    };

    const importJsonFile = (file: File, onImport: (data: any) => void): void => {
        const reader = new FileReader();
    
        reader.onload = (event: ProgressEvent<FileReader>) => {
            try {
                const result = event.target?.result;
                if (typeof result === 'string') {
                    const jsonData = JSON.parse(result);
                    onImport(jsonData);
                }
            } catch (error) {
                console.error('Error parsing JSON file:', error);
                openAlert('error','Error in parsing JSON !');
            }
        };
    
        reader.readAsText(file);
    };

    const fileInputRef = React.useRef<HTMLInputElement>(null);

    const handleFileChange = (event: React.ChangeEvent<HTMLInputElement>): void => {
        const file = event.target.files?.[0];
        if (!file) return;

        importJsonFile(file, (jsonData) => {
            console.log('Imported JSON data:', jsonData);
            setElements(jsonData);
            openAlert('success','Form Imported Successfully !');
            // Handle imported JSON data as needed, e.g., set state or perform actions
        });
    };

    const handleButtonClick = (): void => {
        fileInputRef.current?.click();
    };
    
    
    const handleCloseAlert = (event?: React.SyntheticEvent | Event, reason?: string) => {
        if (reason === 'clickaway') {
          return;
        }
    
        closeAlert();
      };

      const handlePostRequest = async (url:string) => {
        try {
        const data = JSON.stringify(elements);
        const postData = { data };
        const response = await axios.post(url, postData);
        if(response.status === 200){
            toast.success('Form Draft Saved successfully!');
        }
        } catch (error) {
        } finally {
        }
    };

    return (
        <React.Fragment>
            <ButtonGroup
                variant="contained"
                ref={anchorRef}
                aria-label="Button group with a nested menu"
                disableElevation
            >
                <Button onClick={handleClick} sx={{color:'white',border:'2px solid #4f958d',borderRadius:'5px'}}>{options[selectedIndex]}</Button>
                <Button
                    size="small"
                    aria-controls={open ? "split-button-menu" : undefined}
                    aria-expanded={open ? "true" : undefined}
                    aria-label="select merge strategy"
                    aria-haspopup="menu"
                    onClick={handleToggle}
                    sx={{color:'white',border:'2px solid #4f958d',borderRadius:'5px',borderLeft:'none'}}
                >
                    <ArrowDropDownIcon />
                </Button>
            </ButtonGroup>
            <Popper
                sx={{
                    zIndex: 1,
                }}
                open={open}
                anchorEl={anchorRef.current}
                role={undefined}
                transition
                disablePortal
            >
                {({ TransitionProps, placement }) => (
                    <Grow
                        {...TransitionProps}
                        style={{
                            transformOrigin:
                                placement === "bottom"
                                    ? "center top"
                                    : "center bottom",
                        }}
                    >
                        <Paper>
                            <ClickAwayListener onClickAway={handleClose}>
                                <MenuList id="split-button-menu" autoFocusItem>
                                    {options.map((option, index) => (
                                        <MenuItem
                                            key={option}
                                            selected={index === selectedIndex}
                                            onClick={(event) =>
                                                handleMenuItemClick(
                                                    event,
                                                    index
                                                )
                                            }
                                        >
                                            {option}
                                        </MenuItem>
                                    ))}
                                </MenuList>
                            </ClickAwayListener>
                        </Paper>
                    </Grow>
                )}
            </Popper>
            <Snackbar open={alertState.open} autoHideDuration={6000} onClose={handleCloseAlert}>
        <Alert
        onClose={handleCloseAlert}
          severity={alertState.type}
          variant="filled"
          sx={{ width: '100%' }}
        >
          {alertState.message}
        </Alert>
      </Snackbar>
      <input
                type="file"
                ref={fileInputRef}
                onChange={handleFileChange}
                accept=".json"
                style={{ display: 'none' }} // Hide the input element
            />
        </React.Fragment>
    );
}
