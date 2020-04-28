import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { connect } from 'react-redux';

import { validatePlatform } from '../actions/validation';

import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
 
class PlatformCard extends Component {

	constructor(props) {
		super(props);
		this.state = {
			androidStyle: {
				padding: '1rem',
				border: '1px solid rgba(0,0,0,0.5)',
				borderRadius: '2.25rem',
				marginRight: '1rem',
				marginLeft: '0.5rem'
			},
			iosStyle: {
				padding: '1rem',
				border: '1px solid rgba(0,0,0,0.5)',
				borderRadius: '2.25rem'
			},
			selected: false
		}
	}

	handleConfimPlatform() {
		this.props.validatePlatform();
	}

	handleSelectAndroid() {
		this.setState({
			androidStyle: {
				padding: '1rem',
				border: '1px solid rgba(0,0,0,0.5)',
				borderRadius: '2.25rem',
				background: 'rgba(169,211,1,0.2)',
				marginRight: '1rem',
				marginLeft: '0.5rem'
			},
			iosStyle: {
				padding: '1rem',
				border: '1px solid rgba(0,0,0,0.5)',
				borderRadius: '2.25rem'
			},
			selected: true
		})
	}

	handleSelectIOS() {
		this.setState({
			androidStyle: {
				padding: '1rem',
				border: '1px solid rgba(0,0,0,0.5)',
				borderRadius: '2.25rem',
				marginRight: '1rem',
				marginLeft: '0.5rem'
			},
			iosStyle: {
				padding: '1rem',
				border: '1px solid rgba(0,0,0,0.5)',
				borderRadius: '2.25rem',
				background: 'rgba(0,0,0,0.2)'
			},
			selected: true
		})
	}

	render() {

		return (
			<div className="card">
				<div className="card-header" style={{ textAlign: 'center' }}>
					<p style={{ marginBottom: 0, fontSize: '1rem' }}><strong>Step 1. Select your device/platform</strong></p>
					<p style={{ marginBottom: 0, fontWeight: 'bolder' }}>Tap on your platform</p>
				</div>
					<div className="card-body">
						<div className="row">
			                <button className="btn btn-link col-6" style={ this.state.androidStyle } onClick={ this.handleSelectAndroid.bind(this) }>
			                	<img src="http://static.offers.gogameplay.io/images/android.png" style={{ width: '50%' }} />
			                </button>

			                <button className="btn btn-link col-6" style={ this.state.iosStyle } onClick={ this.handleSelectIOS.bind(this) }>
			                	<img src="http://static.offers.gogameplay.io/images/ios.png" style={{ width: '50%' }} />
			                </button>
		                </div>
		            </div>
		            <div className="card-footer">
		            	{(this.state.selected) ? 
		            		<button className="btn btn-success" style={{ width: '100%' }} onClick={ this.handleConfimPlatform.bind(this) }>Next <FontAwesomeIcon icon='angle-right' /></button>
		            		:
		            		<div>
		            			
		            		</div>
		            	}
		            </div>
		    </div>
	    );
	}

}

export default connect(null, { validatePlatform })(PlatformCard);