import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import { connect } from 'react-redux';

import { validatePlatform } from '../actions/validation';

class PlatformCard extends Component {

	constructor(props) {
		super(props);
		this.state = {
			androidStyle: {
				padding: '1rem'
			},
			iosStyle: {
				padding: '1rem'
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
				border: '1px solid #a9d301',
				borderRadius: '2.25rem'
			},
			iosStyle: {
				padding: '1rem'
			},
			selected: true
		})
	}

	handleSelectIOS() {
		this.setState({
			androidStyle: {
				padding: '1rem'
			},
			iosStyle: {
				padding: '1rem',
				border: '1px solid black',
				borderRadius: '2.25rem'
			},
			selected: true
		})
	}

	render() {

		return (
			<div className="card">
				<div className="card-header" style={{ textAlign: 'center' }}>
					<p style={{ marginBottom: 0 }}>1. Select your device/platform</p>
					<small style={{ marginBottom: 0 }}>Tap on your platform</small>
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
		            		<button className="btn btn-success" style={{ width: '100%' }} onClick={ this.handleConfimPlatform.bind(this) }>Next ></button>
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