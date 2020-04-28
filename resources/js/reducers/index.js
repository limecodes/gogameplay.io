import { combineReducers } from 'redux';
import visitorReducer from './visitorReducer';
import validationReducer from './validationReducer';
import carriersReducer from './carriersReducer';

export default combineReducers({
	visitor: visitorReducer,
	validation: validationReducer,
	carriers: carriersReducer
});