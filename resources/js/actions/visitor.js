import {
	SET_VISITOR_STATE_START,
	SET_VISITOR_STATE_COMPLETE,
	SET_VISITOR_STATE_FAIL,
	CONNECTION_CHANGE_START,
	CONNECTION_CHANGE_SUCCESS,
	CONNECTION_CHANGE_FAILURE,
	RECEIVED_CARRIER_LIST_SUCCESS,
	UPDATE_VISITOR_CARRIER_START,
	UPDATE_VISITOR_CARRIER_SUCCESS,
	UPDATE_VISITOR_CARRIER_FAIL
} from './types';

import axios from 'axios';

export const setVisitorData = (device) => async dispatch => {
	const connection = ( (navigator.connection) && (navigator.connection.type == 'cellular') ) ? true : false;

	dispatch({
		type: SET_VISITOR_STATE_START,
		payload: {
			device: device,
			connection: connection
		}
	});

	try {
		const response = await axios.post('/api/visitor/set', {
			device: device,
			connection: connection
		});

		let visitorData;

		if (typeof response.data.carriers_by_country == 'object') {
			dispatch({
				type: RECEIVED_CARRIER_LIST_SUCCESS,
				payload: response.data.carriers_by_country
			});

			visitorData = response.data.visitor;
		} else {
			visitorData = response.data;
		}

		dispatch({
			type: SET_VISITOR_STATE_COMPLETE,
			payload: visitorData
		});
	} catch (error) {
		dispatch({
			type: SET_VISITOR_STATE_FAIL
		});
	}
}

export const connectionChanged = (uid) => async dispatch => {
	dispatch({
		type: CONNECTION_CHANGE_START
	});

	try {
		const response = await axios.post('/api/connectionchanged', {
			uid: uid
		});

		if ( (typeof response.data.carriers_by_country == 'object') && (!response.data.visitor.carrier) ) {
			console.log('got carrier list');
			dispatch({
				type: RECEIVED_CARRIER_LIST_SUCCESS,
				payload: response.data.carriers_by_country
			});
		} else {
			const payload = response.data;

			dispatch({
				type: CONNECTION_CHANGE_SUCCESS,
				payload: payload
			});
		}

	} catch (error) {
		dispatch({
			type: CONNECTION_CHANGE_FAILURE,
			payload: {
				error: true
			}
		});
	}
}

export const updateVisitorCarrier = (uid, carrier) => async dispatch => {
	dispatch({
		type: UPDATE_VISITOR_CARRIER_START
	});

	try {
		const response = await axios.patch('/api/updatecarrier', {
			uid: uid,
			carrier: carrier
		});

		const payload = response.data;

		dispatch({
			type: UPDATE_VISITOR_CARRIER_SUCCESS,
			payload: payload
		});
	} catch (error) {
		dispatch({
			type: UPDATE_VISITOR_CARRIER_FAIL
		});
	}
}
